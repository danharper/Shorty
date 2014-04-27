<?php

require '../vendor/autoload.php';

session_start();

define('METHOD', $_SERVER['REQUEST_METHOD']);
define('PATH', isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/');

$urlRepository = new \Shorty\UrlRepository(new \Shorty\MySqlPdoFactory());

if (METHOD == 'POST' && (PATH == '/' || PATH == ''))
{
	$controller = new \Shorty\Controller\CreateTagController($urlRepository);
	$controller();
	die;
}

if (METHOD == 'GET' && PATH != '/' && PATH != '')
{
	$controller = new \Shorty\Controller\TagRedirectController($urlRepository);
	$controller();
	die;
}

if (METHOD == 'GET' && (PATH == '/' || PATH == ''))
{
	$controller = new \Shorty\Controller\HomeController();
	$controller();
	die;
}
else
{
	die('Unknown Request');
}