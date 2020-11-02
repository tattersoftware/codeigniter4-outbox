<?php namespace Tatter\Outbox\Models;

use CodeIgniter\Model;
use Tatter\Outbox\Entities\Template;

class TemplateModel extends Model
{
	protected $table      = 'outbox_templates';
	protected $primaryKey = 'id';
	protected $returnType = Template::class;

	protected $useSoftDeletes = true;
	protected $useTimestamps  = true;
	protected $skipValidation = true;

	protected $allowedFields = [
		'name', 'subject', 'body', 'tokens', 'parent_id',
	];
}
