<?php namespace Tatter\Outbox\Entities;

use CodeIgniter\Entity\Entity;

class Recipient extends Entity
{
	protected $table = 'outbox_recipients';
	protected $casts = ['outbox_email_id' => 'int'];
}
