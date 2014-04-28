<?php namespace Shorty\Controller;

use Shorty\TagGenerator;
use Shorty\UrlRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class CreateTagController {

	public function __construct(UrlRepository $urlRepository, TagGenerator $tagGenerator)
	{
		$this->urlRepository = $urlRepository;
		$this->tagGenerator = $tagGenerator;
	}

	public function __invoke(Request $request, Session $session)
	{
		$url = $request->request->get('url');

		if ( ! $url) {
			$session->getFlashBag()->add('error_flash', 'No URL Given');
			return new RedirectResponse('/');
		}

		$tag = $this->urlRepository->findTagByUrl($url) ?: $this->tagGenerator->make($url);

		if ($tag)
		{
			return $this->respondWithLink($request, $tag);
		}
		else
		{
			// this was never tested!
			$error = 'Failed to create, try again later';
		}
	}

	private function respondWithLink($request, $tag)
	{
		$host = $request->server->get('HTTP_HOST');
		$link = "http://$host/$tag";
		$view = new \Shorty\View();
		return new Response($view->render('link', ['link' => $link]));
	}

} 