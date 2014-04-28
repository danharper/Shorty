<?php namespace Shorty\Controller;

use Shorty\UrlRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class CreateTagController {

	public function __construct(UrlRepository $urlRepository)
	{
		$this->urlRepository = $urlRepository;
	}

	public function __invoke()
	{
		if ( ! array_key_exists('url', $_POST) || ! $_POST['url']) {
			$_SESSION['error_flash'] = 'No URL Given';
			return new RedirectResponse('/');
		}
		else {
			$url = $_POST['url'];
			$tag = $this->urlRepository->findTagByUrl($url);

			if ($tag)
			{
				$link = 'http://'.$_SERVER['HTTP_HOST'].'/'.$tag;
				$view = new \Shorty\View();
				return new Response($view->render('link', ['link' => $link]));
			}
			else
			{
				$made = false;
				$tries = 0;
				while (!$made && $tries < 5) {
					$chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
					$tag = '';
					for ($i = 0; $i < 10; $i++) $tag .= $chars[rand(0, strlen($chars) - 1)];

					if ($this->urlRepository->insert($tag, $url)) {
						$link = 'http://'.$_SERVER['HTTP_HOST'].'/'.$tag;
						$view = new \Shorty\View();
						return new Response($view->render('link', ['link' => $link]));
					}
					$tries++;
				}
				if (!$made) {
					$error = 'Failed to create, try again later';
				}
			}
		}
	}

} 