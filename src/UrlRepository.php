<?php namespace Shorty;

class UrlRepository {

	public function __construct(PdoFactory $pdo)
	{
		$this->pdo = $pdo;
	}

	public function findUrlByTag($tag)
	{
		$result = $this->select('SELECT url FROM urls WHERE tag = :tag LIMIT 1', ['tag' => $tag]);

		return $result ? $result->url : null;
	}

	public function findTagByUrl($url)
	{
		$result = $this->select('SELECT tag FROM urls WHERE url = :url LIMIT 1', ['url' => $url]);

		return $result ? $result->tag : null;
	}

	public function insert($tag, $url)
	{
		return $this->execute('INSERT INTO urls (tag, url, created_at) VALUES (:tag, :url, NOW())', [
			'tag' => $tag,
			'url' => $url,
		]);
	}

	private function select($query, $inputParameters)
	{
		$stmt = $this->getConnection()->prepare($query);
		$stmt->execute($inputParameters);

		return $stmt->fetchObject();
	}

	/**
	 * @param $query
	 * @param $inputParameters
	 * @return \PDOStatement
	 */
	private function execute($query, $inputParameters)
	{
		return $this->getConnection()->prepare($query)->execute($inputParameters);
	}

	/**
	 * @return \PDO
	 */
	private function getConnection()
	{
		return $this->pdo->getConnection();
	}

}