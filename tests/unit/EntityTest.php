<?php

use Tatter\Outbox\Entities\Attachment;
use Tatter\Outbox\Entities\Email;
use Tatter\Outbox\Entities\Recipient;
use Tatter\Outbox\Models\EmailModel;
use Tests\Support\DatabaseTestCase;

class EntityTest extends DatabaseTestCase
{
	/**
	 * Record of the eamil sent during setUp.
	 *
	 * @var Tatter\Outbox\Entities\Email
	 */
	protected $entity;

	/**
	 * Send an email and fetch the database entry.
	 */
	public function setUp(): void
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
		$this->assertEquals($this->file, $result[0]->name);
	}

	public function testGetRecipients()
	{
		$result = $this->entity->recipients;

		$this->assertCount(1, $result);
		$this->assertInstanceOf(Recipient::class, $result[0]);
		$this->assertEquals('to@example.com', $result[0]->email);
	}
}
