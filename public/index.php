<?php

require '../vendor/autoload.php';

define('TEMPLATE_ROOT', '../web');

$session = new \Symfony\Component\HttpFoundation\Session\Session;
$session->start();

$request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();

$urlRepository = new \Shorty\UrlRepository(new \Shorty\MySqlPdoFactory());
$tagGenerator = new \Shorty\TagGenerator($urlRepository);

$controller = null;

$routes = [
	['GET', '/', function() {
		return new \Shorty\Controller\HomeController();
	}],
	['POST', '/', function() use ($urlRepository, $tagGenerator) {
		return new \Shorty\Controller\CreateTagController($urlRepository, $tagGenerator);
	}],
	['GET', '*', function() use ($urlRepository) {
		return new \Shorty\Controller\TagRedirectController($urlRepository);
	}],
];

function findController($routes, $request) {
	foreach ($routes as list($method, $route, $callable)) {
		if ($request->isMethod($method) && ($request->getPathInfo() == $route || $route == '*')) {
			return $callable;
		}
	}
}

if ($callable = findController($routes, $request))
{
	$controller = $callable();
	$response = $controller($request, $session);
	$response->prepare($request)->send();
	die;
}
else
{
	die('Unknown Request');
}