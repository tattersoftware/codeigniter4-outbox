<?= $this->extend(config('Outbox')->layout) ?>
<?= $this->section('main') ?>

	<div class="row">
		<div class="col">
			<h1>Email Templates</h1>

			<?php if (empty($templates)): ?>
			<p>No templates.</p>
			<?php else: ?>
			<table class="table table-striped">
				<thead>
					<tr>
						<th scope="col">Name</th>
						<th scope="col">Subject</th>
						<th scope="col">Parent</th>
						<th scope="col">Added</th>
						<th scope="col"></th>
					</tr>
				</thead>
				<tbody>

					<?php foreach ($templates as $template): ?>
					<tr>
						<td><?= $template->name ?></td>
						<td><?= $template->subject ?></td>
						<td><?= ($parent = $template->getParent()) ? $parent->name : 'NONE' ?></td>
						<td><?= $template->created_at->format('n/j/Y') ?></td>
						<td>
							<?= anchor('emails/templates/show/' . $template->id, 'View') ?>
							|
							<?= anchor('emails/templates/edit/' . $template->id, 'Edit') ?>
							|
							<?= anchor('emails/templates/new/' . $template->id, 'Clone') ?>
							|
							<?= anchor('emails/templates/send/' . $template->id, 'Send') ?>
							|
							<?= anchor('emails/templates/remove/' . $template->id, 'Remove') ?>
						</td>
					</tr>
					<?php endforeach; ?>

				</tbody>
			</table>
			<?php endif; ?>

		</div>
	</div>

<?= $this->endSection() ?>
