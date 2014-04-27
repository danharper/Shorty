<?php namespace Shorty\Controller;

class HomeController {

	public function __invoke()
	{
		if (array_key_exists('error_flash', $_SESSION)) {
			$error2 = $_SESSION['error_flash'];
			unset($_SESSION['error_flash']);
		}
		else {
			$error = null;
		}

		$view = new \Shorty\View();
		$view->render('home', ['error' => $error2]);

	}

} 