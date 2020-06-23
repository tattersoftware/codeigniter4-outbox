<?php namespace Tatter\Outbox\Config;

use Tatter\Outbox\Models\AttachmentModel;
use Tatter\Outbox\Models\EmailModel;
use Tatter\Outbox\Models\RecipientModel;
use CodeIgniter\Events\Events;

// Intercept successful emails and log them
Events::on('email', function($params)
{
	if (! config('Outbox')->logging)
	{
		return;
	}

	// Create the email record
	if ($id = model(EmailModel::class)->insert($params))
	{
		// Add each recipient
		foreach ($params['recipients'] as $email)
		{
			model(RecipientModel::class)->insert([
				'outbox_email_id' => $id,
				'email'           => $email,
			]);
		}

		// Add each attachment
		foreach ($params['attachments'] as $attachment)
		{
			$test = model(AttachmentModel::class)->insert([
				'outbox_email_id' => $id,
				'name'            => $attachment['name'][0],
				'newName'         => $attachment['name'][1],
				'disposition'     => $attachment['disposition'],
				'type'            => $attachment['type'],
				'multipart'       => $attachment['multipart'],
				'bytes'           => filesize($attachment['name'][0]),
			]);
		}
	}
});
