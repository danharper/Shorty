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

$app = new Kernel;

$app['router']->add('POST', '/', 'Shorty\Controller\CreateTagController');
$app['router']->add('GET', '/', 'Shorty\Controller\HomeController');
$app['router']->add('GET', '/{tag}', 'Shorty\Controller\TagRedirectController');

$app->bind('Shorty\PdoFactory', 'Shorty\MySqlPdoFactory');

try {
	$app()->send();
}
catch (\Symfony\Component\Routing\Exception\ResourceNotFoundException $e) {
	die('Unknown Request');
}
catch (\Symfony\Component\Routing\Exception\MethodNotAllowedException $e) {
	die('Unknown Request');
}

