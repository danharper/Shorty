<?php namespace Shorty\Controller;

use Shorty\TagGenerator;
use Shorty\UrlRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Yolo\View;

class CreateTagController {

	public function __construct(View $view, UrlRepository $urlRepository, TagGenerator $tagGenerator)
	{
		$this->view = $view;
		$this->urlRepository = $urlRepository;
		$this->tagGenerator = $tagGenerator;
	}

	public function __invoke(Request $request)
	{
		$url = $request->request->get('url');

		if ( ! $url) {
			$request->getSession()->getFlashBag()->add('error_flash', 'No URL Given');
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
		return new Response($this->view->render('link', ['link' => $link]));
	}

} 