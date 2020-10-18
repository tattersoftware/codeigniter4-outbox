<?php namespace Tatter\Outbox\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Tatter\Outbox\Entities\Template;
use Tatter\Outbox\Models\TemplateModel;

class Templates extends Controller
{
	/**
	 * The model to use, may be a child of this library's.
	 *
	 * @var TemplateModel
	 */
	protected $model;

	/**
	 * Helpers to load.
	 */
	protected $helpers = ['html', 'text'];

	/**
	 * Preloads the model.
	 *
	 * @param TemplateModel|null $model
	 */
	public function __construct(TemplateModel $model = null)
	{
		$this->model = $model ?? model(TemplateModel::class);
	}

	/**
	 * Gets a Template by its ID.
	 *
	 * @param int $templateId
	 *
	 * @return Template
	 *
	 * @throws PageNotFoundException
	 */
	public function getTemplate(int $templateId): Template
	{
		if ($template = $this->model->find($templateId))
		{
			return $template;
		}

		throw PageNotFoundException::forPageNotFound();
	}

	//--------------------------------------------------------------------

	/**
	 * Lists all available Templates.
	 *
	 * @return string
	 */
	public function index(): string
	{
	}

	/**
	 * Renders a Template.
	 *
	 * @param string|int $templateId
	 *
	 * @return string
	 *
	 * @throws PageNotFoundException
	 */
	public function show($templateId): string
	{
		$template = $this->getTemplate((int) $templateId);

		return $template->body;
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
	public function edit($templateId): string
	{
		return view('Tatter\Outbox\Views\Templates\edit', [
			'template' => $this->getTemplate((int) $templateId),
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
	public function update($templateId): RedirectResponse
	{
		$template = $this->getTemplate((int) $templateId);
	}
}
