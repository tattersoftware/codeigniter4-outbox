<?php

use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\DOMParser;
use Tatter\Outbox\Entities\Template;
use Tatter\Outbox\Models\TemplateModel;
use Tests\Support\OutboxTestCase;

final class ParentTemplateTest extends OutboxTestCase
{
	use DatabaseTestTrait;

	/**
	 * @var DOMParser
	 */
	private $parser;

	/**
	 * @var Template
	 */
	private $parent;

	/**
	 * @var Template
	 */
	private $template;

	public function setUp(): void
	{
		$this->resetServices();
		parent::setUp();

		$this->parser = new DOMParser();

		$this->parent = new Template([
			'name'    => 'Parent Template',
			'subject' => 'Parent {subject}',
			'body'    => '<div>{body}</div><aside>{foobar}</aside>',
		]);
		$this->parent->id = model(TemplateModel::class)->insert($this->parent);

		$this->template = new Template([
			'name'      => 'Test Template',
			'subject'   => 'Some {subject}',
			'body'      => '<p>{number}</p>',
			'parent_id' => $this->parent->id,
		]);
		$this->template->id = model(TemplateModel::class)->insert($this->template);
	}

	public function testChildIncludesParentTokens()
	{
		// Notice that {body} is intentionally excluded
		$expected = ['subject', 'foobar', 'number'];
		$result   = $this->template->getTokens();

		$this->assertEquals($expected, array_values($result));
	}

	public function testChildFallsBackToParentSubject()
	{
		$this->template->subject = '';

		$result = $this->template->getSubject();

		$this->assertEquals('Parent {subject}', $result);
	}

	public function testRenderBodyIncludesParentBody()
	{
		// Expect the child variable in the parent context with inlined CSS
		$expected = '<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">{number}</p>';
		$result   = $this->template->renderBody();

		$this->parser->withString($result);
		$this->assertTrue($this->parser->see('{number}', 'p'));

		$this->assertStringContainsString($expected, $result);
	}

	public function testRenderAppliesTokens()
	{
		$this->parser->withString($this->template->renderBody([
			'number' => 'Banana',
			'foobar' => 'Orange',
		]));

		$this->assertTrue($this->parser->see('Banana', 'div'));
		$this->assertTrue($this->parser->see('Orange', 'aside'));
	}

	public function testRenderInlinesStyles()
	{
		$result = $this->template->renderBody([], 'aside { color: magenta; }');

		$this->parser->withString($result);

		$this->assertTrue($this->parser->see('magenta'));
	}
}
