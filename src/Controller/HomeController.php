<?php namespace Shorty\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class HomeController {

	public function __construct(Session $session)
	{
		$this->session = $session;
	}

	public function __invoke(Request $request)
	{
		$errors = $request->getSession()->getFlashBag()->get('error_flash');
//		$errors = $this->session->getFlashBag()->get('error_flash');
		$error = count($errors) ? $errors[0] : null;

		$view = new \Shorty\View();

		return new Response($view->render('home', ['error' => $error]));
	}

} 