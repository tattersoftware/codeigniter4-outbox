<?php

use CodeIgniter\Test\DatabaseTestTrait;
use Tatter\Outbox\Database\Seeds\TemplateSeeder;
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

	public function testSeederIgnoresExisting()
	{
		$this->seed(TemplateSeeder::class);

		$this->dontSeeInDatabase('outbox_templates', ['name' => 'Default']);
	}

	public function testSeederCreatesDefault()
	{
		model(TemplateModel::class)->truncate();

		$this->seed(TemplateSeeder::class);

		$this->seeInDatabase('outbox_templates', ['name' => 'Default']);
	}
}
