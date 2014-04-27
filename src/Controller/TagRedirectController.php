<?php namespace Shorty\Controller;

use Shorty\UrlRepository;

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
			header("Location: $url", true, 301);
			exit;
		}
		else
		{
			$_SESSION['error_flash'] = 'Shortened URL not found';
			header("Location: /", true, 302);
			die;
		}
	}

} 