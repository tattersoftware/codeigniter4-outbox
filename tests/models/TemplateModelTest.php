<?php

use CodeIgniter\Test\DatabaseTestTrait;
use Tatter\Outbox\Entities\Template;
use Tatter\Outbox\Exceptions\TemplatesException;
use Tatter\Outbox\Models\TemplateModel;
use Tests\Support\OutboxTestCase;

final class TemplateModelTest extends OutboxTestCase
{
	use DatabaseTestTrait;

	/**
	 * @var Template
	 */
	private $template;

	public function setUp(): void
	{
		parent::setUp();

		$this->template = new Template([
			'name'      => 'Test Template',
			'subject'   => 'Some {subject}',
			'body'      => '<p>{number}</p>',
		]);
		$this->template->id = model(TemplateModel::class)->insert($this->template);
	}

	public function testFindByName()
	{
		$result = model(TemplateModel::class)->findByName($this->template->name);

		$this->assertInstanceOf(Template::class, $result);
		$this->assertEquals($this->template->id, $result->id);
	}

	public function testFindByNameThrowsOnFailure()
	{
		$this->expectException(TemplatesException::class);
		$this->expectExceptionMessage(lang('Templates.missingTemplate', ['foobar']));

		model(TemplateModel::class)->findByName('foobar');
	}
}
