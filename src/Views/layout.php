<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="Tatter Software">
	<title>Email Templates</title>

	<script src="https://cdn.jsdelivr.net/npm/tinymce@5.10.2/tinymce.min.js" integrity="sha256-saVT0qXqZ6q6Ztwtmr00aNO4JXwVbZYpZUraZWO6/kI=" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">

	<?= $this->renderSection('headerAssets') ?>

</head>
<body>
	<nav class="navbar navbar-expand-md navbar-dark bg-dark">
		<a class="navbar-brand" href="<?= site_url() ?>">Home</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbars" aria-controls="navbars" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbars">

			<?= view('Tatter\Outbox\Views\navbar') ?>

		</div>
	</nav>

	<?php if ($error = session()->getFlashdata('error')): ?>
	<p class="bg-danger p-3"><?= $error ?></p>
	<?php endif; ?>
	<?php if ($success = session()->getFlashdata('success')): ?>
	<p class="bg-success p-3"><?= $success ?></p>
	<?php endif; ?>

	<main role="main" class="container my-5">

		<?= $this->renderSection('main') ?>

	</main><!-- /.container -->

	<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>

	<?= $this->renderSection('footerAssets') ?>

</body>
</html>
