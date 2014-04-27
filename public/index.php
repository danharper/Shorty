<?php

require '../vendor/autoload.php';

session_start();

define('METHOD', $_SERVER['REQUEST_METHOD']);
define('PATH', isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/');

$urlRepository = new \Shorty\UrlRepository(new \Shorty\MySqlPdoFactory());

if (array_key_exists('error_flash', $_SESSION)) {
	$error = $_SESSION['error_flash'];
	unset($_SESSION['error_flash']);
}
else {
	$error = null;
}

if (METHOD == 'POST' && (PATH == '/' || PATH == ''))
{
	$controller = new \Shorty\Controller\CreateTagController($urlRepository);
	$controller();
	die;
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
		$_SESSION['error_flash'] = 'Shortened URL not found';
		header("Location: /", true, 302);
		die;
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