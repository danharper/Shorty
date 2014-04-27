<?php namespace Shorty\Controller;

use Symfony\Component\HttpFoundation\Response;

class HomeController {

	public function __invoke()
	{
		if (array_key_exists('error_flash', $_SESSION)) {
			$error2 = $_SESSION['error_flash'];
			unset($_SESSION['error_flash']);
		}
		else {
			$error2 = null;
		}

		$view = new \Shorty\View();
		return new Response($view->render('home', ['error' => $error2]));
	}

} 