<?php namespace Shorty\Controller;

use Shorty\UrlRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class TagRedirectController {

	public function __construct(UrlRepository $urlRepository)
	{
		$this->urlRepository = $urlRepository;
	}

	public function __invoke(Request $request)
	{
		$key = ltrim($request->getPathInfo(), '/');

		$url = $this->urlRepository->findUrlByTag($key);

		if ($url)
		{
			return new RedirectResponse($url);
		}
		else
		{
			$_SESSION['error_flash'] = 'Shortened URL not found';
			return new RedirectResponse('/');
		}
	}

} 