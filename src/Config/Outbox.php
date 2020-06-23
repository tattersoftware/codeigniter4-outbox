<?php namespace Tatter\Outbox\Config;

use CodeIgniter\Config\BaseConfig;

class Outbox extends BaseConfig
{
	/**
	 * Whether emails should be logged in the database.
	 *
	 * @var boolean
	 */
	public $logging = true;

	/**
	 * Default view for email templating.
	 *
	 * @var string
	 */
	public $template = 'Tatter\Outbox\Views\layout';

	/**
	 * Default CSS style view to apply to the template.
	 *
	 * @var string
	 */
	public $styles = 'Tatter\Outbox\Views\styles';
}
