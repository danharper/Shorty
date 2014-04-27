<?php namespace Shorty\Test;

use Goutte\Client;

class SystemTest extends \PHPUnit_Framework_TestCase {

	/** @var \Symfony\Component\DomCrawler\Crawler */
	private $crawler;

	public function setUp()
	{
		$client = new Client;
		$this->crawler = $client->request('GET', 'http://localhost:8080');
	}

	public function testItWorks()
	{
		$this->assertTrue(true);
	}

	public function testSiteTitle()
	{
		$this->assertEquals('Shorty', $this->crawler->filter('title')->text());
	}

	public function testFormPostsToSameUrl()
	{
		$form = $this->crawler->filter('form')->form();
		$this->assertEquals('POST', $form->getMethod());
		$this->assertEquals('http://localhost:8080/', $form->getUri());
	}

} 