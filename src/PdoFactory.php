<?php namespace Shorty;

class PdoFactory {

	private $host = 'localhost';
	private $user = 'root';
	private $password = '';
	private $database = 'shorty';

	public function getConnection()
	{
		$db = new \PDO('mysql:host='.$this->host.';dbname='.$this->database, $this->user, $this->password);
		$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		return $db;
	}

} 