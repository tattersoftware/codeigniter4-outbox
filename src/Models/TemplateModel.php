<?php namespace Tatter\Outbox\Models;

use CodeIgniter\Model;
use Tatter\Outbox\Entities\Template;
use Tatter\Outbox\Exceptions\TemplatesException;

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

	/**
	 * Returns a Template by its name, throws if not found.
	 *
	 * @param string $name
	 *
	 * @return Template
	 *
	 * @throws TemplatesException
	 */
	public function findByName(string $name): Template
	{
		if ($template = $this->where('name', $name)->first())
		{
			return $template;
		}

		throw TemplatesException::forMissingTemplate($name);
	}

	/**
	 * Returns a Template by its id, throws if not found.
	 *
	 * @param int $id
	 *
	 * @return Template
	 *
	 * @throws TemplatesException
	 */
	public function findById(int $id): Template
	{
		if ($template = $this->find($id))
		{
			return $template;
		}

		throw TemplatesException::forMissingTemplate($id);
	}
}
