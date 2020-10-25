<?php

use CodeIgniter\Email\Email;
use CodeIgniter\HTTP\Response;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureResponse;
use Tatter\Outbox\Entities\Template;
use Tatter\Outbox\Outbox;

class LibraryTest extends CIUnitTestCase
{
	/**
	 * Instance of the config.
	 *
	 * @var BaseConfig
	 */
	protected $config;

	/**
	 * Response stub so we cna use FeatureResponse for DOM parsing
	 *
	 * @var Response
	 */
	protected $response;

	/**
	 * Some stock data for testing
	 *
	 * @var array
	 */
	protected $data = [
		'title'       => 'First Email Ever',
		'preview'     => 'Please do not think this is spam!',
		'main'        => 'Hey there! Good of you to sign up.<br /> We would like to offer you...',
		'contact'     => 'support@example.com',
		'unsubscribe' => '<a href="https://example.com/unsubscribe">Unsubscribe</a>',	
	];

	public function setUp(): void
	{
		parent::setUp();

		$this->config   = config('Outbox');
		$this->response = new Response(config('App'));
	}

	public function testInlineUsesConfigTemplate()
	{
		$result = Outbox::inline($this->data);

		$parser = new FeatureResponse($this->response->setBody($result));
		$parser->assertSee('Please do not think this is spam!', '.preheader');
	}

	public function testInlineAppliesStyles()
	{
		$result = Outbox::inline($this->data);

		$parser = new FeatureResponse($this->response->setBody($result));
		$parser->assertSee('<td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">');
	}

	public function testTokenizeInjectsTokens()
	{
		$result = Outbox::tokenize();

		$parser = new FeatureResponse($this->response->setBody($result));
		$parser->assertSee('{main}', 'tr');
	}

	public function testFromTemplateReturnsEmail()
	{
		$template = new Template([
			'name'    => 'Test Template',
			'subject' => 'Some {subject}',
			'body'    => '<p>{number}</p>',
			'tokens'  => ['subject', 'number', 'foobar']
		]);

		$result = Outbox::fromTemplate($template);

		$this->assertInstanceOf(Email::class, $result);
	}

	public function testFromTemplateUsesData()
	{
		$template = new Template([
			'name'    => 'Test Template',
			'subject' => 'Some {subject}',
			'body'    => '<p>{number}</p>',
			'tokens'  => ['subject', 'number', 'foobar']
		]);

		$email = Outbox::fromTemplate($template, [
			'subject' => 'pig',
		]);

		$result = $this->getPrivateProperty($email, 'tmpArchive');

		$this->assertEquals('Some pig', $result['subject']);
	}
}

