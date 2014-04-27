<?php namespace Shorty\Test;

use Goutte\Client;

class SystemTest extends \PHPUnit_Framework_TestCase {

	const ROOT_URL = 'http://localhost:8080/';

	/** @var \Symfony\Component\DomCrawler\Crawler */
	private $crawler;

	public function setUp()
	{
		$client = new Client;
		$this->crawler = $client->request('GET', self::ROOT_URL);
	}

	public function testItWorks()
	{
		$this->assertTrue(true);
	}

	public function testSiteTitle()
	{
		$this->assertEquals('Shorty', $this->crawler->filter('title')->text());
	}

	public function testFormWillPostToSelf()
	{
		$form = $this->crawler->filter('form')->form();
		$this->assertEquals('POST', $form->getMethod());
		$this->assertEquals(self::ROOT_URL, $form->getUri());
	}

} 