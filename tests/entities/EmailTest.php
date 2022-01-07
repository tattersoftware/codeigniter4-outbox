<?php

use CodeIgniter\Test\DatabaseTestTrait;
use Tatter\Outbox\Entities\Attachment;
use Tatter\Outbox\Entities\Email;
use Tatter\Outbox\Entities\Recipient;
use Tatter\Outbox\Models\EmailModel;
use Tests\Support\OutboxTestCase;

/**
 * @internal
 */
final class EmailTest extends OutboxTestCase
{
    use DatabaseTestTrait;

    /**
     * Record of the email sent during setUp.
     *
     * @var Tatter\Outbox\Entities\Email
     */
    private $entity;

    /**
     * Send an email and fetch the database entry.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->email
            ->setFrom('from@example.com')
            ->setTo('to@example.com')
            ->setSubject('Email test')
            ->setMessage('This is only a test.')
            ->setMailType('html')
            ->attach($this->file)
            ->send();

        $this->entity = model(EmailModel::class)->first();
    }

    public function testGetAttachments()
    {
        $result = $this->entity->attachments;

        $this->assertCount(1, $result);
        $this->assertInstanceOf(Attachment::class, $result[0]);
        $this->assertSame($this->file, $result[0]->name);
    }

    public function testGetRecipients()
    {
        $result = $this->entity->recipients;

        $this->assertCount(1, $result);
        $this->assertInstanceOf(Recipient::class, $result[0]);
        $this->assertSame('to@example.com', $result[0]->email);
    }
}
