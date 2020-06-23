<?php namespace Tatter\Outbox;

use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

/**
 * Class Outbox
 */
class Outbox
{
	/**
	 * Wrapper function to integrate `CssToInlineStyles` with framework views.
	 *
	 * @param array $data            Data to pass to the view layout (e.g. title, main, etc)
	 * @param string|null $template  View template for the email
	 * @param string|null $styles    View file for the styles
	 *
	 * @return string
	 */
	 public static function inline(array $data, string $template = null, string $styles = null): string
	 {
	 	if (is_null($template))
	 	{
	 		$template = config('Outbox')->template;
	 	}

	 	if (is_null($styles))
	 	{
	 		$styles = config('Outbox')->styles;
	 	}

		return (new CssToInlineStyles)->convert(
			view($template, $data),
			view($styles)
		);
	 }

	/**
	 * Returns a tokenized email based on the default configuration.
	 *
	 * @param array|null $vars   The variables to tokenize
	 *
	 * @return string
	 */
	 public static function tokenize(array $vars = null): string
	 {
	 	// If no variables were specified use the defaults for the bundled template
	 	if (is_null($vars))
	 	{
	 		$vars = ['title', 'preview', 'main', 'contact', 'unsubscribe'];
	 	}

		$data = [];
		foreach ($vars as $var)
		{
			$data[$var] = '{' . $var . '}';
		}

	 	return self::inline($data);
	 }
}
