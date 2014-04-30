<?php namespace Shorty\Controller;

use Shorty\UrlRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class TagRedirectController {

	public function __construct(UrlRepository $urlRepository, Session $session)
	{
		$this->urlRepository = $urlRepository;
		if ( ! $session->isStarted()) throw new \Exception("WIWJ");
		$this->session = $session;
	}

	public function __invoke($tag, Request $request)
	{
		$url = $this->urlRepository->findUrlByTag($tag);

		if ($url)
		{
			return new RedirectResponse($url);
		}
		else
		{
			$this->session->getFlashBag()->add('error_flash', 'Shortened URL not found');
			return new RedirectResponse('/');
		}
	}

} 