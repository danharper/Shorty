<?php namespace Shorty\Test;

use PHPUnit_Extensions_Database_DataSet_IDataSet;
use PHPUnit_Extensions_Database_DB_IDatabaseConnection;
use Shorty\UrlRepository;

class UrlRepositoryTest extends \PHPUnit_Extensions_Database_TestCase {

	/** @var UrlRepository */
	private $sut;

	public function setUp()
	{
		parent::setUp();
		$this->sut = new UrlRepository;
	}

	public function testFindUrlByTagReturnsNullWhenNotFound()
	{
		$this->assertNull($this->sut->findUrlByTag('notexists'));
	}

	public function testFindUrlByTag()
	{
		$this->assertEquals('http://google.com', $this->sut->findUrlByTag('foo'));
	}

	public function testFindTagByUrlReturnsNullWhenNotFound()
	{
		$this->assertNull($this->sut->findTagByUrl('http://notexists.com'));
	}

	public function testFindTagByUrl()
	{
		$this->assertEquals('bar', $this->sut->findTagByUrl('http://facebook.com'));
	}

	public function testInsertTag()
	{
		$this->assertTrue($this->sut->insert('baz', 'http://twitter.com'));

		$this->assertEquals('baz', $this->sut->findTagByUrl('http://twitter.com'));
	}

	protected function getConnection()
	{
		$db = new \PDO('mysql:host=localhost;dbname=shorty', 'root', '');
		$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		return $this->createDefaultDBConnection($db, 'shorty');
	}

	protected function getDataSet()
	{
		return new \PHPUnit_Extensions_Database_DataSet_YamlDataSet(dirname(__FILE__).'/_files/seed.yml');
	}
}