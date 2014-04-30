<?php namespace Shorty\Controller;

use Shorty\UrlRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class TagRedirectController {

	public function __construct(UrlRepository $urlRepository)
	{
		$this->urlRepository = $urlRepository;
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
			$request->getSession()->getFlashBag()->add('error_flash', 'Shortened URL not found');
			return new RedirectResponse('/');
		}
	}

} 