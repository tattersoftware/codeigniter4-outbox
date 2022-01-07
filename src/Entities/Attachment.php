<?php

namespace Tatter\Outbox\Entities;

use CodeIgniter\Entity\Entity;

class Attachment extends Entity
{
    protected $table = 'outbox_attachments';
    protected $casts = [
        'outbox_email_id' => 'int',
        'bytes'           => 'int',
    ];
}
