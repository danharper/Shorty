<?php namespace Yolo;

use Illuminate\Container\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class Kernel extends Container implements HttpKernelInterface {

	public function __construct()
	{
		$this->alias('router', Router::class);
		$this->bindShared('router', function($container) {
			return new Router($container);
		});

		$this->alias('session', Session::class);
		$this->bindShared('session', function() {
			$session = new Session();
			$session->start();
			return $session;
		});

		$this->alias('request', Request::class);
		$this->bindShared('request', function($container) {
			$request = Request::createFromGlobals();
			$request->setSession($container['session']);
			return $request;
		});

		$this->bind(View::class, function($container) {
			return new View($container['config.template_path']);
		});
	}

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
		$response = $this['router']->handle($request);

		return $response;
	}

	public function __invoke()
	{
		return $this->handle($this['request'])->send();
	}

	public function get()
	{
		call_user_func_array([$this['router'], 'add'], array_merge(['GET'], func_get_args()));
	}

	public function post()
	{
		call_user_func_array([$this['router'], 'add'], array_merge(['POST'], func_get_args()));
	}

	public function put()
	{
		call_user_func_array([$this['router'], 'add'], array_merge(['PUT'], func_get_args()));
	}

	public function delete()
	{
		call_user_func_array([$this['router'], 'add'], array_merge(['DELETE'], func_get_args()));
	}

}