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
	 * Layout to use for template management.
	 *
	 * @var string
	 */
	public $layout = 'Tatter\Outbox\Views\layout';

	/**
	 * Default view for email templating.
	 *
	 * @var string
	 */
	public $template = 'Tatter\Outbox\Views\template';

	/**
	 * Default CSS style view to apply to the template.
	 *
	 * @var string
	 */
	public $styles = 'Tatter\Outbox\Views\styles';
}
