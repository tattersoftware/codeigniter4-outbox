<?php namespace App\Config;

/***
*
* This file contains example values to alter default library behavior.
* Recommended usage:
*	1. Copy the file to app/Config/
*	2. Change any values
*	3. Remove any lines to fallback to defaults
*
***/

class Outbox extends \Tatter\Outbox\Config\Outbox
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
