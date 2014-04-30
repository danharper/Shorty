<?php

use Illuminate\Container\Container;
use Shorty\ControllerResolver;
use Shorty\Kernel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

require '../vendor/autoload.php';

define('TEMPLATE_ROOT', '../web');

$kernel = new Kernel;

$session = new Session;
$session->start();

$request = Request::createFromGlobals();
$request->setSession($session);

$container = new Container();
$container->bind('Shorty\PdoFactory', 'Shorty\MySqlPdoFactory');
$container->instance(Session::class, $session);

$routes = new RouteCollection();

$context = new RequestContext();
$context->fromRequest($request);

$matcher = new UrlMatcher($routes, $context);

$resolver = new ControllerResolver(null, $container);

$kernel->instance(ControllerResolverInterface::class, $resolver);
$kernel->instance(UrlMatcherInterface::class, $matcher);

$routes->add('create_tag', (new Route('/', ['_controller' => 'Shorty\Controller\CreateTagController']))->setMethods('POST'));
$routes->add('home', (new Route('/', ['_controller' => 'Shorty\Controller\HomeController']))->setMethods('GET'));
$routes->add('redirect_tag', (new Route('/{tag}', ['_controller' => 'Shorty\Controller\TagRedirectController']))->setMethods('GET'));

try {
	$response = $kernel->handle($request);
	$response->send();
}
catch (\Symfony\Component\Routing\Exception\ResourceNotFoundException $e) {
	die('Unknown Request');
}
catch (\Symfony\Component\Routing\Exception\MethodNotAllowedException $e) {
	die('Unknown Request');
}

