<?php namespace Shorty;

use Illuminate\Container\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;

class Kernel extends Container implements HttpKernelInterface {

	/**
	 * Handles a Request to convert it to a Response.
	 * When $catch is true, the implementation must catch all exceptions
	 * and do its best to convert them to a Response instance.
	 * @param Request $request A Request instance
	 * @param int $type The type of the request
	 *                          (one of HttpKernelInterface::MASTER_REQUEST or HttpKernelInterface::SUB_REQUEST)
	 * @param bool $catch Whether to catch exceptions or not
	 * @return Response A Response instance
	 * @throws \Exception When an Exception occurs during processing
	 * @api
	 */
	public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
	{
		$matcher = $this[UrlMatcherInterface::class];

		$request->attributes->add($matcher->match($request->getPathInfo()));

		$resolver = $this[ControllerResolverInterface::class];

		$controller = $resolver->getController($request);
		$arguments = $resolver->getArguments($request, $controller);

		$response = call_user_func_array($controller, $arguments);

		$response->prepare($request);

		return $response;
	}
	
} 