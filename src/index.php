<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_TABLE', 'shorty');

define('METHOD', $_SERVER['REQUEST_METHOD']);
define('PATH', isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : '/');

$error = null;

$db = @mysql_connect(DB_HOST, DB_USER, DB_PASS) or die ('Can\'t connect to DB server');
@mysql_select_db(DB_TABLE, $db) or die ('Database not found on server');

if (METHOD == 'POST' && (PATH == '/' || PATH == ''))
{
	if ( ! array_key_exists('url', $_POST) || ! $_POST['url']) {
		$error = 'No URL Given';
	}
	else {
		$url = $_POST['url'];
		$result = @mysql_query("SELECT tag FROM urls WHERE url = '$url' LIMIT 1") or die ('Error');
		$row = mysql_fetch_array($result);
		$tag = $row['tag'];

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

				if (@mysql_query("INSERT INTO urls (tag, url, created_at) VALUES ('$tag', '$url', NOW())")) {
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

	$result = @mysql_query("SELECT url FROM urls WHERE tag = '$key' LIMIT 1") or die ('Error');
	$row = mysql_fetch_array($result);
	$url = $row['url'];

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