<?php

use CodeIgniter\Email\Email;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DOMParser;
use Tatter\Outbox\Entities\Template;

/**
 * @internal
 */
final class TemplateTest extends CIUnitTestCase
{
    protected DOMParser $parser;
    protected Template $template;

    protected function setUp(): void
    {
        $this->resetServices();
        parent::setUp();

        $this->parser   = new DOMParser();
        $this->template = new Template([
            'name'    => 'Test Template',
            'subject' => 'Some {subject}',
            'body'    => '<p>{number}</p>',
        ]);
    }

    public function testGetTokensMatchesAll()
    {
        $result = $this->template->getTokens();

        $this->assertSame(['subject', 'number'], $result);
    }

    public function testRenderBodyReturnsBody()
    {
        $this->parser->withString($this->template->renderBody());

        $this->assertTrue($this->parser->see('{number}', 'p'));
    }

    public function testRenderBodyAppliesTokens()
    {
        $this->parser->withString($this->template->renderBody(['number' => 'Banana']));

        $this->assertTrue($this->parser->see('Banana', 'p'));
    }

    public function testRenderBodyInlinesStyles()
    {
        $this->parser->withString($this->template->renderBody([], 'p { color: magenta; }'));

        $this->assertTrue($this->parser->see('magenta'));
    }

    public function testEmailReturnsEmailInstance()
    {
        $template = new Template([
            'name'    => 'Test Template',
            'subject' => 'Some {subject}',
            'body'    => '<p>{number}</p>',
        ]);

        $result = $template->email();

        $this->assertInstanceOf(Email::class, $result);
    }

    public function testEmailUsesData()
    {
        $template = new Template([
            'name'    => 'Test Template',
            'subject' => 'Some {subject}',
            'body'    => '<p>{number}</p>',
        ]);

        $email = $template->email([
            'subject' => 'pig',
        ]);

        $result = $this->getPrivateProperty($email, 'tmpArchive');

        $this->assertSame('Some pig', $result['subject']);
    }
}
