<?php

use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\Test\ControllerTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;
use Tatter\Outbox\Controllers\Templates;
use Tatter\Outbox\Entities\Template;
use Tatter\Outbox\Models\TemplateModel;
use Tests\Support\OutboxTestCase;

/**
 * @internal
 */
final class ControllerTest extends OutboxTestCase
{
    use ControllerTestTrait;
    use DatabaseTestTrait;

    /**
     * @var Templates|null
     */
    protected $controller;

    /**
     * A test Template
     */
    private Template $template;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller(Templates::class);

        $this->template = new Template([
            'name'    => 'Test Template',
            'subject' => 'Some {subject}',
            'body'    => '<p>{number}</p>',
        ]);
        $this->template->id = model(TemplateModel::class)->insert($this->template);
    }

    public function testGetTemplate()
    {
        $result = $this->controller->getTemplate($this->template->id);

        $this->assertSame($this->template->name, $result->name);
    }

    public function testGetTemplateThrows()
    {
        $this->expectException(PageNotFoundException::class);

        $this->controller->getTemplate(42);
    }

    public function testIndexListsTemplates()
    {
        $result = $this->execute('index');

        $result->assertStatus(200);
        $result->assertSee($this->template->name);
    }
}
