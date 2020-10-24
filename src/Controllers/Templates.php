<?php namespace Tatter\Outbox\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\RESTful\ResourcePresenter;
use Tatter\Outbox\Entities\Template;
use Tatter\Outbox\Models\TemplateModel;
use Tatter\Outbox\Outbox;

class Templates extends ResourcePresenter
{
	/**
	 *
	 * @var string|null Name of the model class managing this resource's data
	 */
	protected $modelName = TemplateModel::class;

	/**
	 * Helpers to load.
	 */
	protected $helpers = ['html', 'text'];

	/**
	 * Gets a Template by its ID.
	 *
	 * @param string|int|null $templateId
	 *
	 * @return Template
	 *
	 * @throws PageNotFoundException
	 */
	public function getTemplate($templateId): Template
	{
		if (! is_null($templateId) && $template = $this->model->find($templateId))
		{
			return $template;
		}

		throw PageNotFoundException::forPageNotFound();
	}

	//--------------------------------------------------------------------

	/**
	 * Displays the form to add a Template, with an optional one to copy.
	 *
	 * @param string|int|null $templateId Another template to use as a starting point
	 *
	 * @return string
	 */
	public function new($templateId = null): string
	{
		$template = is_null($templateId) ? new Template() : $this->model->find($templateId);

		return view('Tatter\Outbox\Views\Templates\form', [
			'method'   => $template->id ? 'Clone' : 'New',
			'template' => $template,
		]);
	}

	/**
	 * Creates a new Template.
	 *
	 * @return RedirectResponse
	 */
	public function create(): RedirectResponse
	{
		if ($this->model->insert($this->request->getPost()))
		{
			return redirect()->to(site_url('emails/templates'))->with('success', 'Email template created');
		}

		return redirect()->back()->withInput()->with('error', $this->model->error());
	}

	/**
	 * Lists all available Templates.
	 *
	 * @return string
	 */
	public function index(): string
	{
		return view('Tatter\Outbox\Views\Templates\index', [
			'templates' => $this->model->orderBy('name')->findAll(),
		]);
	}

	/**
	 * Renders a Template for previewing.
	 *
	 * @param string|int $templateId
	 *
	 * @return string
	 *
	 * @throws PageNotFoundException
	 */
	public function show($templateId = null): string
	{
		return $this->getTemplate($templateId)->render();
	}

	/**
	 * Displays the form to edit a Template.
	 *
	 * @param string|int $templateId
	 *
	 * @return string
	 *
	 * @throws PageNotFoundException
	 */
	public function edit($templateId = null): string
	{
		return view('Tatter\Outbox\Views\Templates\form', [
			'method'   => 'Edit',
			'template' => $this->getTemplate($templateId),
		]);
	}

	/**
	 * Updates a Template from posted form data.
	 *
	 * @param string|int $templateId
	 *
	 * @return RedirectResponse
	 *
	 * @throws PageNotFoundException
	 */
	public function update($templateId = null): RedirectResponse
	{
		$template = $this->getTemplate($templateId);

		if ($this->model->update($template->id, $this->request->getPost()))
		{
			return redirect()->back()->with('success', 'Email template updated');
		}

		return redirect()->back()->withInput()->with('error', $this->model->error());
	}

	/**
	 * Deletes a Template.
	 *
	 * @param string|int $templateId
	 *
	 * @return RedirectResponse
	 *
	 * @throws PageNotFoundException
	 */
	public function delete($templateId = null): RedirectResponse
	{
	}
}
