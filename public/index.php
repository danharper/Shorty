<?php

require '../vendor/autoload.php';

define('TEMPLATE_ROOT', '../web');

$session = new \Symfony\Component\HttpFoundation\Session\Session;
$session->start();

$request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();

$container = new \Illuminate\Container\Container();
$container->bind('Shorty\PdoFactory', 'Shorty\MySqlPdoFactory');

$controller = null;

$routes = [
	['GET', '/', 'Shorty\Controller\HomeController'],
	['POST', '/', 'Shorty\Controller\CreateTagController'],
	['GET', '*', 'Shorty\Controller\TagRedirectController'],
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
	$controller = $container->make($callable);
	$response = $controller($request, $session);
	$response->prepare($request)->send();
	die;
}
else
{
	die('Unknown Request');
}