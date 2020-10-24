<?php namespace Tatter\Outbox\Entities;

use CodeIgniter\Entity;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

class Template extends Entity
{
	protected $table = 'outbox_templates';
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts = [
    	'tokens' => 'csv',
    ];

	/**
	 * Renders this Template with inlined CSS.
	 *
	 * @param array $data Variables to exchange for Template tokens
	 * @param string|null $styles CSS to use for inlining, defaults to configured view
	 *
	 * @return string
	 */
	public function render($data = [], string $styles = null): string
	{
		if (empty($this->attributes['body']))
		{
			return '';
		}

		// Replace tokens with $data values
		$body = service('parser')->setData($data)->renderString($this->attributes['body']);

		// Determine styling
		$styles = $styles ?? view(config('Outbox')->styles, [], ['debug' => false]);
		if (empty($styles))
		{
			return $body;
		}

		return (new CssToInlineStyles)->convert(
			$body,
			$styles,
		);
	}
}

