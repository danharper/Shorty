<?php namespace Yolo;

interface PdoFactory {

	/**
	 * @return \PDO
	 */
	public function getConnection();

}