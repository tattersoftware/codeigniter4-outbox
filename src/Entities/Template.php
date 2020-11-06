<?php namespace Tatter\Outbox\Entities;

use CodeIgniter\Email\Email;
use CodeIgniter\Entity;
use Tatter\Outbox\Models\TemplateModel;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

class Template extends Entity
{
	protected $table = 'outbox_templates';
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts = [
    	'parent_id' => '?int',
    ];

	/**
	 * Stored parent Template.
	 *
	 * @var self|null
	 */
	protected $parent;

	//--------------------------------------------------------------------

	/**
	 * Returns the parent Template, if set.
	 *
	 * @return self|null
	 */
	public function getParent(): ?self
	{
		if (! isset($this->attributes['parent_id']))
		{
			return null;
		}

		if (is_null($this->parent))
		{
			$this->parent = model(TemplateModel::class)->find($this->attributes['parent_id']);
		}

		return $this->parent;
	}

	/**
	 * Returns the subject from the parent Template if this one is empty.
	 *
	 * @return string
	 */
	public function getSubject(): string
	{
		if (! empty($this->attributes['subject']))
		{
			return $this->attributes['subject'];
		}

		return $this->getParent() ? $this->parent->subject : '';
	}

	/**
	 * Returns any subject or body tokens from this and parent.
	 *
	 * @return array
	 */
	public function getTokens(): array
	{
		preg_match_all('#\{(\w+)\}#', $this->attributes['subject'] . $this->attributes['body'], $matches);

		if ($parent = $this->getParent())
		{
			$matches[1] = array_unique(array_merge($parent->getTokens(), $matches[1]));
		}

		return $matches[1];
	}

	//--------------------------------------------------------------------

	/**
	 * Renders the body with inlined CSS.
	 *
	 * @param array $data Variables to exchange for Template tokens
	 * @param string|null $styles CSS to use for inlining, defaults to configured view
	 *
	 * @return string
	 */
	public function renderBody($data = [], string $styles = null): string
	{
		if (empty($this->attributes['body']))
		{
			return '';
		}

		// Replace tokens with $data values
		$body = service('parser')
			->setData($data, 'raw')
			->renderString($this->attributes['body'], ['debug' => false]);

		// If this has a parent Template then render it with this body
		if ($parent = $this->getParent())
		{
			$data['body'] = $body;

			return $parent->renderBody($data, $styles);
		}

		// Determine styling
		$styles = $styles ?? view(config('Outbox')->styles, [], ['debug' => false]);

		return $styles === '' ? $body : (new CssToInlineStyles)->convert($body, $styles);
	}

	/**
	 * Renders the subject.
	 *
	 * @param array $data Variables to exchange for Template tokens
	 *
	 * @return string
	 */
	public function renderSubject($data = []): string
	{
		if (! $subject = $this->getSubject())
		{
			return '';
		}

		// Replace tokens with $data values
		return service('parser')
			->setData($data, 'raw')
			->renderString($subject, ['debug' => false]);
	}

	//--------------------------------------------------------------------

	/**
	 * Returns an Email instance primed to this Template's rendered values.
	 *
	 * @param array $data         Variables to use when rendering the body
	 * @param string|null $styles CSS to use for inlining, null to use configured view
	 *
	 * @return Email
	 */
	public function email($data = [], string $styles = null): Email
	{
		// Start with the default config and add necessary settings
		$email = service('email');
		$email->mailType = 'html';
		$email->wordWrap = false;

		// Render the subject and body and return the Email instance
		return $email
			->setSubject($this->renderSubject($data))
			->setMessage($this->renderBody($data, $styles));
	}
}
