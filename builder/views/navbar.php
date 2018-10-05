
<?php

// List/Array of nabvar items
$items = array(
	'module' => array('label' => 'Module', 'link' => 'module.php', 'active' => false),
	'widget' => array('label' => 'Widget', 'link' => 'widget.php', 'active' => false),
	'page' => array('label' => 'Page', 'link' => '#', 'active' => false)
);

// Set active item
if (isset($options['navbar_active'])) {
	$items[$options['navbar_active']]['active'] = true;
}
else {
	$items['module']['active'] = true;
}

?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
	<a class="navbar-brand" href="#">Dolibase Builder</a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>

	<div class="collapse navbar-collapse" id="navbar">
		<ul class="navbar-nav mr-auto">
			<?php foreach ($items as $key => $value) { ?>
				<li class="nav-item<?php echo $value['active'] ? ' active' : ''; ?>">
					<a class="nav-link" href="<?php echo $value['link']; ?>"><?php echo $value['label']; ?></a>
				</li>
			<?php } ?>
		</ul>
	</div>
</nav>
