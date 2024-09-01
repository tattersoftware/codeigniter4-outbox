<?php

namespace Tatter\Outbox\Config;

use CodeIgniter\Events\Events;
use Tatter\Outbox\Models\AttachmentModel;
use Tatter\Outbox\Models\EmailModel;
use Tatter\Outbox\Models\RecipientModel;

// Intercept successful emails and log them
Events::on('email', static function ($params) {
    if (! config('Outbox')->logging) {
        return;
    }

    // Create the email record (ignores insert collisions)
    $id = model(EmailModel::class)->ignore()->insert($params); // @phpstan-ignore-line
    if ($id) {
        // Add each recipient
        foreach ($params['recipients'] as $email) {
            model(RecipientModel::class)->insert([
                'outbox_email_id' => $id,
                'email'           => $email,
            ]);
        }

        // Add each attachment
        foreach ($params['attachments'] as $attachment) {
            $test = model(AttachmentModel::class)->insert([
                'outbox_email_id' => $id,
                'name'            => $attachment['name'][0],
                'newName'         => $attachment['name'][1],
                'disposition'     => $attachment['disposition'],
                'type'            => $attachment['type'],
                'multipart'       => $attachment['multipart'],
                'bytes'           => getRemoteFileSize($attachment['name'][0]) ?? filesize($attachment['name'][0]),
            ]);
        }
    }
});

function getRemoteFileSize($url)
{
    $headers = get_headers($url, 1);

    if (isset($headers['Content-Length'])) {
        return $headers['Content-Length'];
    }

    return false;
}
