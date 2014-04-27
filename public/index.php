<?php

require '../vendor/autoload.php';

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_TABLE', 'shorty');

define('METHOD', $_SERVER['REQUEST_METHOD']);
define('PATH', isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/');

$urlRepository = new \Shorty\UrlRepository();

$error = null;

if (METHOD == 'POST' && (PATH == '/' || PATH == ''))
{
	if ( ! array_key_exists('url', $_POST) || ! $_POST['url']) {
		$error = 'No URL Given';
	}
	else {
		$url = $_POST['url'];
		$tag = $urlRepository->findTagByUrl($url);

		if ($tag)
		{
			$link = 'http://'.$_SERVER['HTTP_HOST'].'/'.$tag;
			die("<a href='$link'>$link</a>");
		}
		else
		{
			$made = false;
			$tries = 0;
			while (!$made && $tries < 5) {
				$chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
				$tag = '';
				for ($i = 0; $i < 10; $i++) $tag .= $chars[rand(0, strlen($chars) - 1)];

				if ($urlRepository->insert($tag, $url)) {
					$link = 'http://'.$_SERVER['HTTP_HOST'].'/'.$tag;
					die("<a href='$link'>$link</a>");
				}
				$tries++;
			}
			if (!$made) {
				$error = 'Failed to create, try again later';
			}
		}
	}
}

if (METHOD == 'GET' && PATH != '/' && PATH != '')
{
	$key = ltrim(PATH, '/');

	$url = $urlRepository->findUrlByTag($key);

	if ($url)
	{
		header("Location: $url", true, 301);
//		die($url);
		exit;
	}
	else
	{
		$error = 'Shortened URL not found';
	}
}

if ((METHOD == 'GET' && (PATH == '/' || PATH == '')) || $error)
{
	?>
	<title>Shorty</title>
	<?php if ($error) : ?>
		<style>.error { color: red; }</style>
		<p class="error"><b>Error:</b> <?php echo $error; ?></p>
	<?php endif; ?>
	<form method="POST" action="/">
		<input name="url">
		<button>Shorten it, Shorty!</button>
	</form>
<?php
}
else
{
	die('Unknown Request');
}