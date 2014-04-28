<?php

use Illuminate\Container\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

require '../vendor/autoload.php';

define('TEMPLATE_ROOT', '../web');

$session = new Session;
$session->start();

$request = Request::createFromGlobals();

$container = new Container();
$container->bind('Shorty\PdoFactory', 'Shorty\MySqlPdoFactory');

$routes = new RouteCollection();
$routes->add('create_tag', (new Route('/', ['controller' => 'Shorty\Controller\CreateTagController']))->setMethods('POST'));
$routes->add('home', (new Route('/', ['controller' => 'Shorty\Controller\HomeController']))->setMethods('GET'));
$routes->add('redirect_tag', (new Route('/{tag}', ['controller' => 'Shorty\Controller\TagRedirectController']))->setMethods('GET'));

$context = new RequestContext();
$context->fromRequest($request);

$matcher = new UrlMatcher($routes, $context);

try {
	$attributes = $matcher->match($request->getPathInfo());
}
catch (\Symfony\Component\Routing\Exception\ResourceNotFoundException $e) {
	die('Unknown Request');
}
catch (\Symfony\Component\Routing\Exception\MethodNotAllowedException $e) {
	die('Unknown Request');
}

$controller = $attributes['controller'];
$controller = $container->make($controller);

$response = $controller($request, $session);
$response->prepare($request)->send();
