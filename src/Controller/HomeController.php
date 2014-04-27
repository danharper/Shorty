<?php namespace Shorty\Controller;

class HomeController {

	public function __invoke()
	{
		if (array_key_exists('error_flash', $_SESSION)) {
			$error = $_SESSION['error_flash'];
			unset($_SESSION['error_flash']);
		}
		else {
			$error = null;
		}

		include TEMPLATE_ROOT.'/home.tmpl.php';
	}

} 