
<?php

// List/Array of nabvar items
$items = array(
	'module' => array('label' => 'Module', 'link' => 'module.php', 'icon' => 'cube', 'active' => false),
	'widget' => array('label' => 'Widget', 'link' => 'widget.php', 'icon' => 'dashboard', 'active' => false),
	'page' => array('label' => 'Page', 'link' => '#', 'icon' => 'file-o', 'active' => false, 'menu' => array(
		'index' => array('label' => 'Index Page', 'link' => 'page/index.php', 'active' => false),
		'create' => array('label' => 'Create Page', 'link' => 'page/create.php', 'active' => false),
		'card' => array('label' => 'Card Page', 'link' => 'page/card.php', 'active' => false),
		'list' => array('label' => 'List Page', 'link' => 'page/list.php', 'active' => false),
		'stats' => array('label' => 'Statistics Page', 'link' => 'page/stats.php', 'active' => false),
		'calendar' => array('label' => 'Calendar Page', 'link' => 'page/calendar.php', 'active' => false),
		'document' => array('label' => 'Document Page', 'link' => 'page/document.php', 'active' => false),
		'log' => array('label' => 'Log Page', 'link' => 'page/log.php', 'active' => false)
	)),
	'model' => array('label' => 'Model', 'link' => '#', 'icon' => 'clone', 'active' => false, 'menu' => array(
		'object_class' => array('label' => 'Object Class', 'link' => 'model/object_class.php', 'active' => false),
		'num_model' => array('label' => 'Numbering Model', 'link' => 'model/num_model.php', 'active' => false),
		'doc_model' => array('label' => 'Document Model', 'link' => 'model/doc_model.php', 'active' => false)
	)),
	'translation' => array('label' => 'Translation', 'link' => 'translation.php', 'icon' => 'language', 'active' => false)
);

// Set active item
if (isset($options['navbar_active'])) {
	$active = explode('/', $options['navbar_active']);
	$items[$active[0]]['active'] = true;
	if (isset($active[1])) {
		$items[$active[0]]['menu'][$active[1]]['active'] = true; // activate menu item also
	}
}
else {
	$items['module']['active'] = true;
}

// Update items links
if (isset($options['path_prefix'])) {
	foreach ($items as $key => $value) {
		if (! empty($value['link']) && $value['link'] != '#') {
			$items[$key]['link'] = $options['path_prefix'].$value['link']; // e.: replace 'module.php' with '../module.php'
		}
		else if (isset($value['menu']) && ! empty($value['menu'])) {
			foreach ($value['menu'] as $menu_key => $menu_item) {
				$items[$key]['menu'][$menu_key]['link'] = $options['path_prefix'].$menu_item['link'];
			}
		}
	}
}

?>

<aside id="sidebar" class="collapse d-md-block col-12 col-md-2 p-0 py-1 bg-light border-right">
	<nav class="navbar navbar-light p-0">
		<ul class="navbar-nav w-100">
			<?php foreach ($items as $key => $value) { ?>
				<?php if (isset($value['menu']) && ! empty($value['menu'])) { ?>
					<li class="nav-item<?php echo $value['active'] ? ' active' : ''; ?>">
						<a class="nav-link px-4 py-3" href="<?php echo $value['link']; ?>" data-toggle="collapse" data-target="#<?php echo $key; ?>">
							<i class="fa fa-<?php echo $value['icon']; ?> fa-fw"></i> <?php echo $value['label']; ?><i class="fa fa-angle-down fa-fw pull-right"></i>
						</a>
						<ul class="collapse<?php echo $value['active'] ? ' show' : ''; ?>" id="<?php echo $key; ?>">
							<?php foreach ($value['menu'] as $menu_item) { ?>
								<li class="nav-item<?php echo $menu_item['active'] ? ' active' : ''; ?>">
									<a class="nav-link" href="<?php echo $menu_item['link']; ?>"><?php echo $menu_item['label']; ?></a>
								</li>
							<?php } ?>
						</ul>
					</li>
				<?php } else { ?>
					<li class="nav-item<?php echo $value['active'] ? ' active' : ''; ?>">
						<a class="nav-link px-4 py-3" href="<?php echo $value['link']; ?>"><i class="fa fa-<?php echo $value['icon']; ?> fa-fw"></i> <?php echo $value['label']; ?></a>
					</li>
				<?php } ?>
			<?php } ?>
		</ul>
	</nav>
</aside>
