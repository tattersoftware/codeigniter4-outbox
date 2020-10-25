<?php

use CodeIgniter\Test\DOMParser;
use Tatter\Outbox\Entities\Template;
use Tests\Support\DatabaseTestCase;

class EmailEntityTest extends DatabaseTestCase
{
	/**
	 * @var DOMParser
	 */
	protected $parser;

	/**
	 * @var Template
	 */
	protected $template;

	public function setUp(): void
	{
		parent::setUp();

		$this->parser   = new DOMParser();
		$this->template = new Template([
			'name'    => 'Test Template',
			'subject' => 'Some {subject}',
			'body'    => '<p>{number}</p>',
			'tokens'  => ['subject', 'number', 'foobar']
		]);
	}

	public function testRenderReturnsBody()
	{
		$this->parser->withString($this->template->render());

		$this->assertTrue($this->parser->see('{number}', 'p'));
	}

	public function testRenderAppliesTokens()
	{
		$this->parser->withString($this->template->render(['number' => 'Banana']));

		$this->assertTrue($this->parser->see('Banana', 'p'));
	}

	public function testRenderInlinesStyles()
	{
		$this->parser->withString($this->template->render([], 'p { color: magenta; }'));

		$this->assertTrue($this->parser->see('magenta'));
	}
}
