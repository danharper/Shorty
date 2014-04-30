<?php namespace Shorty;

class TagGenerator {

	public function __construct(UrlRepository $urlRepository)
	{
		$this->urlRepository = $urlRepository;
	}

	public function make($url)
	{
		$tries = 0;

		while ($tries < 5) {
			$tag = $this->createRandomString();

			if ($this->urlRepository->insert($tag, $url)) {
				return $tag;
			}

			$tries++;
		}

		return false;
	}

	/**
	 * @return string
	 */
	private function createRandomString($length = 10)
	{
		$chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$tag = '';
		for ($i = 0; $i < $length; $i ++)
			$tag .= $chars[rand(0, strlen($chars) - 1)];

		return $tag;
	}

} 