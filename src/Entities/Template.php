<?php namespace Tatter\Outbox\Entities;

use CodeIgniter\Entity;

class Template extends Entity
{
	protected $table = 'outbox_templates';
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
}

