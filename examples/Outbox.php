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
