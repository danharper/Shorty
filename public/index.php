<?php

require '../vendor/autoload.php';

define('TEMPLATE_ROOT', '../web');

$session = new \Symfony\Component\HttpFoundation\Session\Session;
$session->start();

$request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();

$controller = null;

$routes = [
	['GET', '/', function() {
		$container = new \Illuminate\Container\Container();
		return $container->make('Shorty\Controller\HomeController');
	}],
	['POST', '/', function() {
		$container = new \Illuminate\Container\Container();
		$container->bind('Shorty\PdoFactory', 'Shorty\MySqlPdoFactory');
		return $container->make('Shorty\Controller\CreateTagController');
	}],
	['GET', '*', function() {
		$container = new \Illuminate\Container\Container();
		$container->bind('Shorty\PdoFactory', 'Shorty\MySqlPdoFactory');
		return $container->make('Shorty\Controller\TagRedirectController');
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