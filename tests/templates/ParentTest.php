<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DOMParser;
use Tatter\Outbox\Entities\Template;
use Tatter\Outbox\Models\TemplateModel;
use Tests\Support\DatabaseTestCase;

class ParentTest extends DatabaseTestCase
{
	/**
	 * @var DOMParser
	 */
	protected $parser;

	/**
	 * @var Template
	 */
	protected $parent;

	/**
	 * @var Template
	 */
	protected $template;

	public function setUp(): void
	{
		$this->resetServices();
		parent::setUp();

		$this->parser = new DOMParser();

		$this->parent = new Template([
			'name'    => 'Parent Template',
			'subject' => 'Parent {subject}',
			'body'    => '<div>{body}</div><aside>{foobar}</aside>',
			'tokens'  => ['subject', 'body', 'foobar'],
		]);
		$this->parent->id = model(TemplateModel::class)->insert($this->parent);

		$this->template = new Template([
			'name'      => 'Test Template',
			'subject'   => 'Some {subject}',
			'body'      => '<p>{number}</p>',
			'tokens'    => ['subject', 'number', 'foobar'],
			'parent_id' => $this->parent->id,
		]);
		$this->template->id = model(TemplateModel::class)->insert($this->template);
	}

	public function testRenderReturnsParentBody()
	{
		// Expect the child variable in the parent context with inlined CSS
		$expected = '<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">{number}</p>';
		$result   = $this->template->render();

		$this->parser->withString($result);
		$this->assertTrue($this->parser->see('{number}', 'p'));

		$this->assertStringContainsString($expected, $result);
	}

	public function testRenderAppliesTokens()
	{
		$this->parser->withString($this->template->render([
			'number' => 'Banana',
			'foobar' => 'Orange',
		]));

		$this->assertTrue($this->parser->see('Banana', 'div'));
		$this->assertTrue($this->parser->see('Orange', 'aside'));
	}

	public function testRenderInlinesStyles()
	{
		$result = $this->template->render([], 'aside { color: magenta; }');

		$this->parser->withString($result);

		$this->assertTrue($this->parser->see('magenta'));
	}
}
