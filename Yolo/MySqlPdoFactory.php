<?php namespace Yolo;

class MySqlPdoFactory implements PdoFactory {

	private $host;
	private $user;
	private $password;
	private $database;

	public function __construct($host, $user, $password, $database)
	{
		$this->host = $host;
		$this->user = $user;
		$this->password = $password;
		$this->database = $database;
	}

	/**
	 * @return \PDO
	 */
	public function getConnection()
	{
		$db = new \PDO('mysql:host='.$this->host.';dbname='.$this->database, $this->user, $this->password);
		$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		return $db;
	}

} 