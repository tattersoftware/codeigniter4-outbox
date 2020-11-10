<?php namespace Tatter\Outbox\Entities;

use CodeIgniter\Entity;

class Email extends Entity
{
	protected $table = 'outbox_emails';
	protected $dates = [
		'created_at',
		'updated_at',
	];

	/**
	 * Array of field names and the type of value to cast them as
	 * when they are accessed.
	 */
	protected $casts = [
		'priority'      => 'int',
		'sendMultipart' => 'boolean',
		'BCCBatchMode'  => 'boolean',
		'BCCBatchSize'  => 'int',
	];

	/**
	 * Attachments cache
	 *
	 * @var array|null
	 */
	protected $attachments;

	/**
	 * Recipients cache
	 *
	 * @var array|null
	 */
	protected $recipients;

	/**
	 * Returns this email's attachments.
	 *
	 * @return array
	 */
	public function getAttachments(): array
	{
		return $this->getRelatedItems('attachment');
	}

	/**
	 * Returns this email's recipients.
	 *
	 * @return array
	 */
	public function getRecipients(): array
	{
		return $this->getRelatedItems('recipient');
	}

	/**
	 * Helper function to return attachments or recipients.
	 *
	 * @param string $target Object/table/model to request
	 *
	 * @return array
	 */
	protected function getRelatedItems(string $target): array
	{
		if (empty($this->id))
		{
			throw new \RuntimeException('Object must be created before getting relations.');
		}

		$property = $target . 's';
		$model    = 'Tatter\Outbox\Models\\' . ucfirst($target) . 'Model';

		if (is_null($this->$property))
		{
			$this->$property = model($model)->where('outbox_email_id', $this->id)->findAll();
		}

		return $this->$property;
	}
}
