
<?php

// List/Array of nabvar items
$items = array(
	'module' => array('label' => 'Module', 'link' => 'module.php', 'icon' => 'cube', 'active' => false),
	'widget' => array('label' => 'Widget', 'link' => 'widget.php', 'icon' => 'dashboard', 'active' => false),
	'page' => array('label' => 'Page', 'link' => '#', 'icon' => 'file-o', 'active' => false)
);

// Set active item
if (isset($options['navbar_active'])) {
	$items[$options['navbar_active']]['active'] = true;
}
else {
	$items['module']['active'] = true;
}

?>

<aside class="sidebar col-12 col-md-2 p-0">
	<nav class="navbar navbar-expand navbar-dark flex-md-column flex-row align-items-start px-0 py-2">
		<div class="collapse navbar-collapse w-100">
			<ul class="flex-md-column flex-row navbar-nav w-100 justify-content-between">
				<?php foreach ($items as $key => $value) { ?>
					<li class="nav-item<?php echo $value['active'] ? ' active' : ''; ?>">
						<a class="nav-link p-3" href="<?php echo $value['link']; ?>"><i class="fa fa-<?php echo $value['icon']; ?> fa-fw"></i> <span class="d-none d-md-inline"><?php echo $value['label']; ?></span></a>
					</li>
				<?php } ?>
			</ul>
		</div>
	</nav>
</aside>
