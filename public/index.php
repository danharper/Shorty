<?php

use Illuminate\Container\Container;
use Shorty\ControllerResolver;
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
$request->setSession($session);

$container = new Container();
$container->bind('Shorty\PdoFactory', 'Shorty\MySqlPdoFactory');
$container->instance(Session::class, $session);

$routes = new RouteCollection();
$routes->add('create_tag', (new Route('/', ['_controller' => 'Shorty\Controller\CreateTagController']))->setMethods('POST'));
$routes->add('home', (new Route('/', ['_controller' => 'Shorty\Controller\HomeController']))->setMethods('GET'));
$routes->add('redirect_tag', (new Route('/{tag}', ['_controller' => 'Shorty\Controller\TagRedirectController']))->setMethods('GET'));

$context = new RequestContext();
$context->fromRequest($request);

$matcher = new UrlMatcher($routes, $context);

$resolver = new ControllerResolver(null, $container);

try {
	$request->attributes->add($matcher->match($request->getPathInfo()));

	$controller = $resolver->getController($request);
	$arguments = $resolver->getArguments($request, $controller);
//	var_dump($arguments); die;

//	$response = $controller($request, $session);
	$response = call_user_func_array($controller, $arguments);
	$response->prepare($request)->send();
}
catch (\Symfony\Component\Routing\Exception\ResourceNotFoundException $e) {
	die('Unknown Request');
}
catch (\Symfony\Component\Routing\Exception\MethodNotAllowedException $e) {
	die('Unknown Request');
}

