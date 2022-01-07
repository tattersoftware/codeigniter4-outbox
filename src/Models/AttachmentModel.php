<?php

namespace Tatter\Outbox\Models;

use CodeIgniter\Model;
use Tatter\Outbox\Entities\Attachment;

class AttachmentModel extends Model
{
    protected $table          = 'outbox_attachments';
    protected $primaryKey     = 'id';
    protected $returnType     = Attachment::class;
    protected $useSoftDeletes = false;
    protected $useTimestamps  = false;
    protected $skipValidation = true;
    protected $allowedFields  = [
        'outbox_email_id', 'name', 'newName', 'disposition', 'type', 'multipart', 'bytes',
    ];
}
