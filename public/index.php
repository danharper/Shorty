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

$router = $kernel['router'];

$router->addRoute('create_tag', (new Route('/', ['_controller' => 'Shorty\Controller\CreateTagController']))->setMethods('POST'));
$router->addRoute('home', (new Route('/', ['_controller' => 'Shorty\Controller\HomeController']))->setMethods('GET'));
$router->addRoute('redirect_tag', (new Route('/{tag}', ['_controller' => 'Shorty\Controller\TagRedirectController']))->setMethods('GET'));

$kernel->bind('Shorty\PdoFactory', 'Shorty\MySqlPdoFactory');

try {
	$kernel()->send();
}
catch (\Symfony\Component\Routing\Exception\ResourceNotFoundException $e) {
	die('Unknown Request');
}
catch (\Symfony\Component\Routing\Exception\MethodNotAllowedException $e) {
	die('Unknown Request');
}

