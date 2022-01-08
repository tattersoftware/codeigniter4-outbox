<?php $this->extend(config('Layouts')->outbox) ?>
<?php $this->section('navbar') ?>

	<?= view('Tatter\Outbox\Views\navbar') ?>

<?php $this->endSection() ?>
<?php $this->section('main') ?>

	<div class="row">
		<div class="col">
			<h1><?= $method ?> Template</h1>
			<h2 class="mb-3"><?= $template->name ?></h2>

			<form name="email-template-form" action="<?= site_url('emails/templates/' . ($method === 'Edit' ? 'update/' . $template->id : 'create')) ?>" method="post">
				<div class="form-group">
					<label for="parent_id">Parent Template</label>
					<select name="parent_id">
						<option value="">NONE</option>

						<?php foreach ($templates as $option): ?>
						<option value="<?= $option->id ?>" <?= $option->id === $template->parent_id ? 'selected' : '' ?>><?= $option->name ?></option>
						<?php endforeach; ?>

					</select>
				</div>
				<div class="form-group">
					<label for="name">Name</label>
					<input name="name" type="text" class="form-control" id="name" value="<?= old('name', $template->name) ?>">
				</div>
				<div class="form-group">
					<label for="subject">Subject</label>
					<input name="subject" type="text" class="form-control" id="subject" value="<?= old('subject', $template->subject) ?>">
				</div>
				<div class="form-group">
					<label for="body">Body</label>
					<textarea name="body" class="form-control" id="body" rows="25" style="font-family: monospace;"><?= old('body', $template->body) ?></textarea>
				</div>
				<div class="form-group">
					<input name="template_id" type="hidden" value="<?= $template->id ?>">
					<button type="submit" class="btn btn-primary">Submit</button>
				</div>
			</form>

		</div>
	</div>

<?php $this->endSection() ?>
