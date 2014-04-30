<?php namespace Shorty\Test;

use PDO;
use Goutte\Client;

class SystemTest extends \PHPUnit_Framework_TestCase {

	const ROOT_URL = 'http://localhost:8080/';

	/** @var  \Goutte\Client */
	private $client;

	/** @var  \Symfony\Component\DomCrawler\Crawler */
	private $crawler;

	/** @var  \PDO */
	private $db;

	public function setUp()
	{
		$this->client = new Client;
		$this->crawler = $this->client->request('GET', self::ROOT_URL);

		$this->db = (new \Yolo\MySqlPdoFactory('localhost', 'root', '', 'shorty'))->getConnection();
		$this->db->exec("DELETE FROM urls");
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

	public function testFormHasUrlInput()
	{
		$form = $this->crawler->filter('form')->form();
		$this->assertTrue($form->has('url'));
	}

	public function testFormHasButton()
	{
		$formNode = $this->crawler->filter('form');
		$this->assertGreaterThanOrEqual(1, $formNode->filter('button')->count());
	}

	public function testSubmittingFormSavesToDb()
	{
		$this->submitForm('http://google.com/');
		$this->assertEquals(1, $this->db->query('SELECT * FROM urls WHERE url = "http://google.com/"')->rowCount());
	}

	public function testSubmittingFormGivesShortenedUrl()
	{
		$nextPage = $this->submitForm('http://google.com/');
		$link = $nextPage->filter('a')->attr('href');

		$dbRow = $this->db->query('SELECT * FROM urls WHERE url = "http://google.com/"')->fetchObject();

		$this->assertEquals(self::ROOT_URL.$dbRow->tag, $link);
	}

	public function testSubmittingEmptyFormGivesError()
	{
		$nextPage = $this->submitForm('');
		$this->assertEquals('Error: No URL Given', $nextPage->filter('p.error')->text());
		$this->assertEquals(0, $this->db->query('SELECT * FROM urls')->rowCount());
	}

	public function testSubmittingSameUrlTwiceUsesSameTag()
	{
		$pageA = $this->submitForm('http://google.co.uk');
		$pageB = $this->submitForm('http://google.co.uk');

		$this->assertEquals(1, $this->db->query('SELECT * FROM urls')->rowCount());

		$this->assertEquals($pageA->filter('a')->attr('href'), $pageB->filter('a')->attr('href'));
	}

	public function testRedirectsShortenedUrlToFullUrl()
	{
		$this->db->exec('INSERT INTO urls (tag, url, created_at) VALUES ("foo", "http://facebook.com", NOW())');

		$this->client->followRedirects(false);
		$this->client->request('GET', self::ROOT_URL.'foo');

		$response = $this->client->getResponse();

		$this->assertEquals(302, $response->getStatus());
		$this->assertEquals('http://facebook.com', $response->getHeader('location'));
	}

	public function testRequestingBadUrlShowsError()
	{
		$crawler = $this->client->request('GET', self::ROOT_URL.'foo');
		$this->assertEquals('Error: Shortened URL not found', $crawler->filter('p.error')->text());
	}

	public function testCompletelyBadRequest()
	{
		$crawler = $this->client->request('POST', self::ROOT_URL.'foo');
		$this->assertEquals('Unknown Request', $crawler->text());
	}

	/**
	 * @param $inputUrl
	 * @return \Symfony\Component\DomCrawler\Crawler
	 */
	private function submitForm($inputUrl)
	{
		$form = $this->crawler->filter('form')->form();
		$form['url'] = $inputUrl;
		$nextPage = $this->client->submit($form);

		return $nextPage;
	}

} 