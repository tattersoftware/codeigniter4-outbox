<?php namespace Tatter\Outbox\Models;

use CodeIgniter\Model;
use Tatter\Outbox\Entities\Email;

class EmailModel extends Model
{
	protected $table      = 'outbox_emails';
	protected $primaryKey = 'id';
	protected $returnType = Email::class;

	protected $useSoftDeletes = false;
	protected $useTimestamps  = true;
	protected $skipValidation = true;

	protected $allowedFields = [
		'subject', 'body', 'fromEmail', 'fromName', 'userAgent', 'protocol', 'mailType',
		'charset', 'priority', 'sendMultipart', 'BCCBatchMode', 'BCCBatchSize',
		'template', 'template_id',
	];
}
