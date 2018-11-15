
<?php if (! isset($options) || ! is_array($options)) die('BuilderError: options array not set.'); ?>

<?php $assets_prefix = isset($options['path_prefix']) ? $options['path_prefix'] : ''; ?>

<!DOCTYPE html>
<html lang="en" class="h-100">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="Dolibase Builder">
		<meta name="author" content="AXeL">
		<link rel="icon" href="<?php echo $assets_prefix; ?>assets/favicon.ico">

		<title><?php echo $options['title']; ?></title>

		<!-- CSS -->
		<link href="<?php echo $assets_prefix; ?>assets/css/bootstrap/bootstrap.min.css" rel="stylesheet">
		<link href="<?php echo $assets_prefix; ?>assets/css/font-awesome.min.css" rel="stylesheet">
		<?php
			foreach ($options['css'] as $css_filename) {
				echo '<link href="'.$assets_prefix.'assets/css/'.$css_filename.'" rel="stylesheet">'."\n";
			}
		?>
	</head>

	<body class="h-100">

		<!-- Top NavBar -->
		<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
			<a class="navbar-brand" href="#"><i class="fa fa-rocket fa-fw"></i> Dolibase Builder</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidebar" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
		</nav>

		<!-- Container -->
		<div class="container-fluid h-100">
			<div class="row h-100">

				<!-- SideBar -->
				<?php include_once __DIR__ . '/sidebar.php'; ?>

				<!-- Main -->
				<main class="col p-3">

					<!-- Message -->
					<?php if (isset($options['message']) && ! empty($options['message'])) { ?>
						<div class="alert alert-<?php echo $options['message']['type']; ?> alert-dismissible fade show" role="alert">
							<?php echo $options['message']['text']; ?>
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
					<?php } ?>

					<!-- Form -->
					<?php include_once __DIR__ . '/form/'.$options['form_name'].'.php'; ?>

				</main>
			</div>
		</div>

		<!-- JavaScript -->
		<script src="<?php echo $assets_prefix; ?>assets/js/jquery.min.js"></script>
		<script src="<?php echo $assets_prefix; ?>assets/js/bootstrap/bootstrap.min.js"></script>
		<?php
			foreach ($options['js'] as $js_filename) {
				echo '<script src="'.$assets_prefix.'assets/js/'.$js_filename.'"></script>'."\n";
			}
		?>
	</body>
</html>
