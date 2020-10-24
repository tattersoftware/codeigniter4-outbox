<?= $this->extend(config('Outbox')->layout) ?>
<?= $this->section('main') ?>

	<div class="row">
		<div class="col">
			<h1><?= $method ?> Template</h1>
			<h2 class="mb-3"><?= $template->name ?></h2>

			<form name="email-template-form" action="<?= site_url('emails/templates/' . ($method === 'Edit' ? 'update/' . $template->id : 'create')) ?>" method="post">
				<div class="form-group">
					<label for="tokens">Available Tokens</label>
					<br />
					<?php if ($method === 'Edit'): ?>
					<?php foreach ($template->tokens as $token): ?>
					<span class="badge badge-secondary"><?= $token ?></span>
					<?php endforeach; ?>
					<?php endif; ?>

					<input type="<?= $method === 'Edit' ? 'hidden' : 'text' ?>"
						class="form-control"
						name="tokens"
						id="tokens"
						placeholder="token1,token2,token3,..."
						value="<?= implode(',', $template->tokens) ?>"
					/>
				</div>
				<div class="form-group">
					<label for="name">Name</label>
					<input name="name" type="text" class="form-control" id="name" value="<?= $template->name ?>">
				</div>
				<div class="form-group">
					<label for="subject">Subject</label>
					<input name="subject" type="text" class="form-control" id="subject" value="<?= $template->subject ?>">
				</div>
				<div class="form-group">
					<label for="body">Body</label>
					<textarea name="body" class="form-control" id="body" rows="30" style="font-family: monospace;"><?= $template->body ?></textarea>
				</div>
				<div class="form-group">
					<input name="template_id" type="hidden" value="<?= $template->id ?>">
					<button type="submit" class="btn btn-primary">Submit</button>
				</div>
			</form>

		</div>
	</div>

<?= $this->endSection() ?>
