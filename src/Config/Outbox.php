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
	 * Whether to include routes to the Templates Controller.
	 *
	 * @var boolean
	 */
	public $routeTemplates = false;

	/**
	 * Layout to use for Template management.
	 *
	 * @var string
	 */
	public $layout = 'Tatter\Outbox\Views\layout';

	/**
	 * View path for the default CSS styles to inline.
	 *
	 * @var string
	 */
	public $styles = 'Tatter\Outbox\Views\Defaults\styles';
}
