<?php

use CodeIgniter\Config\Config;
use CodeIgniter\Test\DatabaseTestTrait;
use Tatter\Outbox\Entities\Template;
use Tatter\Outbox\Models\AttachmentModel;
use Tatter\Outbox\Models\TemplateModel;
use Tests\Support\OutboxTestCase;

final class EventTest extends OutboxTestCase
{
	use DatabaseTestTrait;

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

	public function testEventRespectsConfigLogging()
	{
		$config = config('Outbox');
		$config->logging = false;
		Config::injectMock('Outbox', $config);

		$result = $this->email
			->setFrom('from@example.com')
			->setSubject('Email test')
			->setMessage('This is only a test.')
			->setMailType('html')
			->send();

		$this->assertTrue($result);
		$this->dontSeeInDatabase('outbox_emails', ['subject' => 'Email test']);
	}

	public function testEventRecordsTemplate()
	{
		$templateId = model(TemplateModel::class)->insert(new Template([
			'name'      => 'Test Template',
			'subject'   => 'Some {subject}',
			'body'      => '<p>{number}</p>',
		]));

		$template = model(TemplateModel::class)->findByName('Test Template');
		$template->email()->send();

		$this->seeInDatabase('outbox_emails', ['template' => 'Test Template']);
		$this->seeInDatabase('outbox_emails', ['template_id' => $templateId]);
	}
}
