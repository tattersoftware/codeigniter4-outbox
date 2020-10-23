<?= $this->extend($config->layouts['public']) ?>
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
						<th scope="col">Added</th>
					</tr>
				</thead>
				<tbody>

					<?php foreach ($templates as $template): ?>
					<tr>
						<td><?= $template->name ?></td>
						<td><?= $template->subject ?></td>
						<td><?= $template->createdAt->format('n/j/Y') ?></td>
					</tr>
					<?php endforeach; ?>

				</tbody>
			</table>
			<?php endif; ?>

		</div>
	</div>

<?= $this->endSection() ?>
