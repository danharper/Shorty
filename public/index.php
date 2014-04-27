<?php

require '../vendor/autoload.php';

define('TEMPLATE_ROOT', '../web');

session_start();

define('METHOD', $_SERVER['REQUEST_METHOD']);
define('PATH', isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/');

$urlRepository = new \Shorty\UrlRepository(new \Shorty\MySqlPdoFactory());

$controller = null;

if (METHOD == 'POST' && (PATH == '/' || PATH == ''))
{
	$controller = new \Shorty\Controller\CreateTagController($urlRepository);
}

if (METHOD == 'GET' && PATH != '/' && PATH != '')
{
	$controller = new \Shorty\Controller\TagRedirectController($urlRepository);
}

if (METHOD == 'GET' && (PATH == '/' || PATH == ''))
{
	$controller = new \Shorty\Controller\HomeController();
}

if ($controller)
{
	echo $controller();
	die;
}
else
{
	die('Unknown Request');
}