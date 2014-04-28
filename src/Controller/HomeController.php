<?php namespace Shorty\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class HomeController {

	public function __invoke(Request $request, Session $session)
	{
		$errors = $session->getFlashBag()->get('error_flash');
		$error = count($errors) ? $errors[0] : null;

		$view = new \Shorty\View();
		return new Response($view->render('home', ['error' => $error]));
	}

} 