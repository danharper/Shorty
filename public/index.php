<?php

require '../vendor/autoload.php';

define('TEMPLATE_ROOT', '../web');

session_start();

$request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();

$urlRepository = new \Shorty\UrlRepository(new \Shorty\MySqlPdoFactory());
$tagGenerator = new \Shorty\TagGenerator($urlRepository);

$controller = null;

if ($request->getMethod() == 'POST' && ($request->getPathInfo() == '/' || $request->getPathInfo() == ''))
{
	$controller = new \Shorty\Controller\CreateTagController($urlRepository, $tagGenerator);
}

if ($request->getMethod() == 'GET' && $request->getPathInfo() != '/' && $request->getPathInfo() != '')
{
	$controller = new \Shorty\Controller\TagRedirectController($urlRepository);
}

if ($request->getMethod() == 'GET' && ($request->getPathInfo() == '/' || $request->getPathInfo() == ''))
{
	$controller = new \Shorty\Controller\HomeController();
}

if ($controller)
{
	$response = $controller($request);
	$response->prepare($request)->send();
	die;
}
else
{
	die('Unknown Request');
}