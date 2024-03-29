<?php

use CodeIgniter\Test\DatabaseTestTrait;
use Tatter\Outbox\Database\Seeds\TemplateSeeder;
use Tatter\Outbox\Entities\Template;
use Tatter\Outbox\Exceptions\TemplatesException;
use Tatter\Outbox\Models\TemplateModel;
use Tests\Support\OutboxTestCase;

/**
 * @internal
 */
final class TemplateModelTest extends OutboxTestCase
{
    use DatabaseTestTrait;

    private Template $template;

    protected function setUp(): void
    {
        parent::setUp();

        $this->template = new Template([
            'name'    => 'Test Template',
            'subject' => 'Some {subject}',
            'body'    => '<p>{number}</p>',
        ]);
        $this->template->id = model(TemplateModel::class)->insert($this->template);
    }

    public function testFindByName()
    {
        $result = (new TemplateModel())->findByName($this->template->name);

        $this->assertInstanceOf(Template::class, $result);
        $this->assertSame($this->template->id, $result->id);
    }

    public function testFindByNameThrowsOnFailure()
    {
        $this->expectException(TemplatesException::class);
        $this->expectExceptionMessage(lang('Templates.missingTemplate', ['foobar']));

        (new TemplateModel())->findByName('foobar');
    }

    public function testSeederIgnoresExisting()
    {
        $this->seed(TemplateSeeder::class);

        $this->dontSeeInDatabase('outbox_templates', ['name' => 'Default']);
    }

    public function testSeederCreatesDefault()
    {
        (new TemplateModel())->builder()->truncate();

        $this->seed(TemplateSeeder::class);

        $this->seeInDatabase('outbox_templates', ['name' => 'Default']);
    }
}
