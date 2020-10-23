<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="Tatter Software">
	<title>Email Templates</title>

	<?= service('assets')->tag('vendor/jquery/jquery.min.js') ?>
	<?= service('assets')->tag('vendor/bootstrap/bootstrap.min.css') ?>

	<?= service('alerts')->css() ?>
</head>
<body>
	<nav class="navbar navbar-expand-md navbar-dark bg-dark">
		<a class="navbar-brand" href="<?= site_url() ?>">Home</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbars" aria-controls="navbars" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbars">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item">
					<a class="nav-link" href="<?= site_url('emails/templates') ?>">List Templates</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?= site_url('emails/templates/new') ?>">Add Template</a>
				</li>
			</ul>
		</div>
	</nav>

	<?php if ($error = session()->getFlashdata('error'): ?>
	<p class="bg-danger"><?= $error ?></p>
	<?php endif; ?>
	<?php if ($success = session()->getFlashdata('success'): ?>
	<p class="bg-success"><?= $success ?></p>
	<?php endif; ?>

	<main role="main" class="container my-5">

		<?= $this->renderSection('main') ?>

	</main><!-- /.container -->

	<?= service('assets')->tag('vendor/bootstrap/bootstrap.bundle.min.js') ?>

</body>
</html>
