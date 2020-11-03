<?php namespace Tatter\Outbox;

use CodeIgniter\Email\Email;
use Tatter\Outbox\Entities\Template;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

/**
 * Class Outbox
 */
class Outbox
{
	/**
	 * Renders a Template with inlined CSS.
	 *
	 * @param Template $template
	 * @param array $data Variables to exchange for Template tokens
	 * @param string|null $styles CSS to use for inlining, defaults to configured view
	 *
	 * @return Email
	 */
	public static function fromTemplate(Template $template, $data = [], string $styles = null): Email
	{
		// Start with default config and add necessary settings
		$email = service('email');
		$email->mailType = 'html';
		$email->wordWrap = false;

		// Replace tokens with $data values
		$email->setSubject(service('parser')->setData($data)->renderString($template->subject, ['debug' => false]));
		$email->setMessage($template->render($data, $styles));

		return $email;
	}

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

		return (new CssToInlineStyles)->convert(view($template, $data, ['debug' => false]), view($styles, [], ['debug' => false]));
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
	 		$vars = ['title', 'preview', 'body', 'contact', 'unsubscribe'];
	 	}

		$data = [];
		foreach ($vars as $var)
		{
			$data[$var] = '{' . $var . '}';
		}

	 	return self::inline($data);
	 }
}
