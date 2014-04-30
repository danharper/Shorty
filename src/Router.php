<?php namespace Shorty;

use Illuminate\Container\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class Router implements HttpKernelInterface {

	/**
	 * @var \Illuminate\Container\Container
	 */
	protected $container;

	/**
	 * @var \Symfony\Component\Routing\RouteCollection
	 */
	protected $routes;

	public function __construct(Container $container)
	{
		$this->container = $container;
		$this->routes = new RouteCollection();
	}

	public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
	{
		$context = new RequestContext();
		$context->fromRequest($request);

		$matcher = new UrlMatcher($this->routes, $context);

		$resolver = new ControllerResolver(null, $this->container);

		$request->attributes->add($matcher->match($request->getPathInfo()));

		$controller = $resolver->getController($request);
		$arguments = $resolver->getArguments($request, $controller);

		$response = call_user_func_array($controller, $arguments);

		$response->prepare($request);

		return $response;
	}

	public function addRoute($name, Route $route)
	{
		$this->routes->add($name, $route);
	}

}