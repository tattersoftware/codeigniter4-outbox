<?php

namespace Tatter\Outbox\Models;

use CodeIgniter\Model;
use Tatter\Outbox\Entities\Recipient;

class RecipientModel extends Model
{
    protected $table          = 'outbox_recipients';
    protected $primaryKey     = 'id';
    protected $returnType     = Recipient::class;
    protected $useSoftDeletes = false;
    protected $useTimestamps  = false;
    protected $skipValidation = true;
    protected $allowedFields  = ['outbox_email_id', 'email'];
}
