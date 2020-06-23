<?php

use CodeIgniter\HTTP\Response;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureResponse;
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
}

