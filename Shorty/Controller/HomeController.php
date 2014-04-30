<?php namespace Shorty\Controller;

use Yolo\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController {

	public function __construct(View $view)
	{
		$this->view = $view;
	}

	public function __invoke(Request $request)
	{
		$errors = $request->getSession()->getFlashBag()->get('error_flash');
		$error = count($errors) ? $errors[0] : null;

		return new Response($this->view->render('home', ['error' => $error]));
	}

} 