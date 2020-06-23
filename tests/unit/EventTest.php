<?php

use Tatter\Outbox\Models\AttachmentModel;
use Tests\Support\DatabaseTestCase;

class EventTest extends DatabaseTestCase
{
	public function testEventCreatesEmail()
	{
		$result = $this->email
			->setFrom('from@example.com')
			->setSubject('Email test')
			->setMessage('This is only a test.')
			->setMailType('html')
			->send();

		$this->assertTrue($result);
		$this->seeInDatabase('outbox_emails', ['subject' => 'Email test']);
	}

	public function testEventCreatesRecipient()
	{
		$result = $this->email
			->setFrom('from@example.com')
			->setTo('to@example.com')
			->setSubject('Email test')
			->setMessage('This is only a test.')
			->setMailType('html')
			->send();

		$this->assertTrue($result);
		$this->seeInDatabase('outbox_recipients', ['email' => 'to@example.com']);
	}

	public function testEventCreatesAttachment()
	{
		$result = $this->email
			->setFrom('from@example.com')
			->setSubject('Email test')
			->setMessage('This is only a test.')
			->setMailType('html')
			->attach($this->file)
			->send();

		$this->assertTrue($result);
		$this->seeInDatabase('outbox_attachments', ['name' => $this->file]);
	}

	public function testAttachmentHasFilesize()
	{
		$result = $this->email
			->setFrom('from@example.com')
			->setSubject('Email test')
			->setMessage('This is only a test.')
			->setMailType('html')
			->attach($this->file)
			->send();

		$this->assertTrue($result);
		
		$attachment = model(AttachmentModel::class)->first();
		$this->assertEquals(12026, $attachment->bytes);
	}
}
