<?php namespace Shorty;

class UrlRepository {

	public function findUrlByTag($tag)
	{
		$result = $this->execute('SELECT url FROM urls WHERE tag = :tag LIMIT 1', ['tag' => $tag]);

		return $result ? $result->url : null;
	}

	public function findTagByUrl($url)
	{
		$result = $this->execute('SELECT tag FROM urls WHERE url = :url LIMIT 1', ['url' => $url]);

		return $result ? $result->tag : null;
	}

	private function execute($query, $inputParameters)
	{
		$db = new \PDO('mysql:host=localhost;dbname=shorty', 'root', '');
		$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

		$stmt = $db->prepare($query);
		$stmt->execute($inputParameters);

		$result = $stmt->fetchObject();

		return $result;
	}

}