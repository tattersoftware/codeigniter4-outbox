<?php

namespace Tatter\Outbox\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\RESTful\ResourcePresenter;
use Tatter\Outbox\Entities\Template;
use Tatter\Outbox\Models\TemplateModel;

class Templates extends ResourcePresenter
{
    /**
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
     * @param int|string|null $templateId
     *
     * @throws PageNotFoundException
     */
    public function getTemplate($templateId): Template
    {
        if (null !== $templateId && $template = $this->model->find($templateId)) {
            return $template;
        }

        throw PageNotFoundException::forPageNotFound();
    }

    //--------------------------------------------------------------------

    /**
     * Displays the form to add a Template, with an optional one to copy.
     *
     * @param int|string|null $templateId Another template to use as a starting point
     */
    public function new($templateId = null): string
    {
        $template = null === $templateId ? new Template() : $this->model->find($templateId);

        return view('Tatter\Outbox\Views\Templates\form', [
            'method'    => $template->id ? 'Clone' : 'New',
            'template'  => $template,
            'templates' => $this->model->orderBy('name')->findAll(),
        ]);
    }

    /**
     * Creates a new Template.
     */
    public function create(): RedirectResponse
    {
        if ($this->model->insert($this->request->getPost())) {
            return redirect()->to(site_url('emails/templates'))->with('success', 'Email template created.');
        }

        return redirect()->back()->withInput()->with('error', $this->model->errors());
    }

    /**
     * Lists all available Templates.
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
     * @param int|string $templateId
     *
     * @throws PageNotFoundException
     */
    public function show($templateId = null): string
    {
        return $this->getTemplate($templateId)->renderBody();
    }

    /**
     * Displays the form to edit a Template.
     *
     * @param int|string $templateId
     *
     * @throws PageNotFoundException
     */
    public function edit($templateId = null): string
    {
        return view('Tatter\Outbox\Views\Templates\form', [
            'method'    => 'Edit',
            'template'  => $this->getTemplate($templateId),
            'templates' => $this->model->where('id !=', $templateId)->orderBy('name')->findAll(),
        ]);
    }

    /**
     * Updates a Template from posted form data.
     *
     * @param int|string $templateId
     *
     * @throws PageNotFoundException
     */
    public function update($templateId = null): RedirectResponse
    {
        $template = $this->getTemplate($templateId);

        if ($this->model->update($template->id, $this->request->getPost())) {
            return redirect()->back()->with('success', 'Email template updated.');
        }

        return redirect()->back()->withInput()->with('error', $this->model->errors());
    }

    /**
     * Deletes a Template.
     *
     * @param int|string $templateId
     *
     * @throws PageNotFoundException
     */
    public function remove($templateId = null): RedirectResponse
    {
        $template = $this->getTemplate($templateId);

        if ($this->model->delete($template->id)) {
            return redirect()->back()->with('success', 'Email template removed.');
        }

        return redirect()->back()->withInput()->with('error', $this->model->errors());
    }

    /**
     * Displays the form to send an email.
     *
     * @param int|string $templateId
     *
     * @throws PageNotFoundException
     */
    public function send($templateId = null): string
    {
        return view('Tatter\Outbox\Views\Templates\send', [
            'template' => $this->getTemplate($templateId),
        ]);
    }

    /**
     * Sends an email using the Template.
     *
     * @param int|string $templateId
     *
     * @throws PageNotFoundException
     */
    public function send_commit($templateId = null): RedirectResponse
    {
        if (! $this->validate([
            'fromEmail' => 'valid_email',
            'recipients' => 'valid_emails',
        ])) {
            return redirect()->back()->withInput()->with('error', implode('. ', $this->validator->getErrors()));
        }

        $email = $this->getTemplate($templateId)->email($this->request->getPost());

        $email->setFrom($this->request->getPost('fromEmail'), $this->request->getPost('fromName'));
        $email->setTo($this->request->getPost('recipients'));

        if ($email->send(false)) {
            return redirect()->back()->with('success', 'Email sent');
        }

        return redirect()->back()->withInput()->with('error', $email->printDebugger([]));
    }
}
