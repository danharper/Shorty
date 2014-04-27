<?php namespace Shorty\Controller;

use Shorty\UrlRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;

class TagRedirectController {

	public function __construct(UrlRepository $urlRepository)
	{
		$this->urlRepository = $urlRepository;
	}

	public function __invoke()
	{
		$key = ltrim(PATH, '/');

		$url = $this->urlRepository->findUrlByTag($key);

		if ($url)
		{
			return new RedirectResponse($url, 301);
		}
		else
		{
			$_SESSION['error_flash'] = 'Shortened URL not found';
			return new RedirectResponse('/');
		}
	}

} 