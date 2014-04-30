<?php namespace Yolo;

use Illuminate\Container\Container;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolver as SymfonyControllerResolver;

class ControllerResolver extends SymfonyControllerResolver {

	/**
	 * @var \Illuminate\Container\Container
	 */
	private $container;

	public function __construct(LoggerInterface $logger = null, Container $container)
	{
		parent::__construct($logger);
		$this->container = $container;
	}

	public function getController(Request $request)
	{
		if (! $controller = $request->attributes->get('_controller'))
		{
			if (null !== $this->logger)
			{
				$this->logger->warning('Unable to look for the controller as the "_controller" parameter is missing');
			}

			return false;
		}

		if (is_array($controller))
		{
			return $controller;
		}

		if (is_object($controller))
		{
			if (method_exists($controller, '__invoke'))
			{
				return $controller;
			}

			throw new \InvalidArgumentException(sprintf('Controller "%s" for URI "%s" is not callable.', get_class($controller), $request->getPathInfo()));
		}

		if (false === strpos($controller, ':'))
		{
			if (method_exists($controller, '__invoke'))
			{
				return $this->instantiateController($controller);
			}
			elseif (function_exists($controller))
			{
				return $controller;
			}
		}

		$callable = $this->createController($controller);

		if (! is_callable($callable))
		{
			throw new \InvalidArgumentException(sprintf('Controller "%s" for URI "%s" is not callable.', $controller, $request->getPathInfo()));
		}

		return $callable;
	}

	/**
	 * Returns a callable for the given controller.
	 * @param string $controller A Controller string
	 * @return mixed A PHP callable
	 * @throws \InvalidArgumentException
	 */
	protected function createController($controller)
	{
		if (false === strpos($controller, '::'))
		{
			throw new \InvalidArgumentException(sprintf('Unable to find controller "%s".', $controller));
		}

		list($class, $method) = explode('::', $controller, 2);

		if (! class_exists($class))
		{
			throw new \InvalidArgumentException(sprintf('Class "%s" does not exist.', $class));
		}

		return array($this->instantiateController($class), $method);
	}

	protected function instantiateController($class)
	{
		return $this->container->make($class);
	}

} 