<?php

require '../vendor/autoload.php';

define('TEMPLATE_ROOT', '../web');

$session = new \Symfony\Component\HttpFoundation\Session\Session;
$session->start();

$request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();

$urlRepository = new \Shorty\UrlRepository(new \Shorty\MySqlPdoFactory());
$tagGenerator = new \Shorty\TagGenerator($urlRepository);

$controller = null;

if ($request->isMethod('POST') && ($request->getPathInfo() == '/' || $request->getPathInfo() == ''))
{
	$controller = new \Shorty\Controller\CreateTagController($urlRepository, $tagGenerator);
}

if ($request->isMethod('GET') && $request->getPathInfo() != '/' && $request->getPathInfo() != '')
{
	$controller = new \Shorty\Controller\TagRedirectController($urlRepository);
}

if ($request->isMethod('GET') && ($request->getPathInfo() == '/' || $request->getPathInfo() == ''))
{
	$controller = new \Shorty\Controller\HomeController();
}

if ($controller)
{
	$response = $controller($request, $session);
	$response->prepare($request)->send();
	die;
}
else
{
	die('Unknown Request');
}