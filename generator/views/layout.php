
<?php if (! isset($options) || ! is_array($options)) die('Dolibase::Generator::Error options array not set.'); ?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="Dolibase Generator">
		<meta name="author" content="AXeL">
		<link rel="icon" href="favicon.ico">

		<title><?php echo $options['title']; ?></title>

		<!-- CSS -->
		<link href="css/bootstrap/bootstrap.min.css" rel="stylesheet">
		<?php
			foreach ($options['css'] as $css_filename) {
				echo '<link href="css/'.$css_filename.'" rel="stylesheet">'."\n";
			}
		?>
	</head>

	<body>

		<!-- NavBar -->
		<?php include_once __DIR__ . '/navbar.php'; ?>

		<!-- Container -->
		<div class="container py-5">

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
		</div>

		<!-- JavaScript -->
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap/bootstrap.min.js"></script>
		<?php
			foreach ($options['js'] as $js_filename) {
				echo '<script src="js/'.$js_filename.'"></script>'."\n";
			}
		?>
	</body>
</html>
