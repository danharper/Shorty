<?php namespace Shorty;

interface PdoFactory {

	/**
	 * @return \PDO
	 */
	public function getConnection();

}