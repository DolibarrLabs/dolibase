
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" class="px-2">
	<div class="form-group">
		<label for="module_folder">Module</label>
		<select class="form-control" id="module_folder" name="module_folder" required>
			<?php foreach ($options['modules_list'] as $module) { ?>
				<option value="<?php echo $module; ?>"><?php echo $module; ?></option>
			<?php } ?>
		</select>
	</div>
	<div class="form-group">
		<label for="widget_name">Widget Name</label>
		<input type="text" class="form-control" id="widget_name" name="widget_name" placeholder="MyWidget" required>
	</div>
	<div class="form-group">
		<label for="widget_position">Widget Position</label>
		<input type="number" class="form-control" id="widget_position" name="widget_position" placeholder="1" min="1" value="1" required>
	</div>
	<div class="form-group">
		<label for="widget_picture">Widget Picture</label>
		<input type="file" accept="image/*" class="form-control-file" id="widget_picture" name="widget_picture" required>
	</div>
	<div class="form-group">
		<label for="widget_title">Widget Title</label>
		<input type="text" class="form-control" id="widget_title" name="widget_title" aria-describedby="widgetTitleHelp" placeholder="MyWidgetTitle" required>
		<small id="widgetTitleHelp" class="form-text text-muted">Title to display on Dolibarr dashboard</small>
	</div>
	<div class="form-group form-check">
		<input type="checkbox" class="form-check-input" id="enable_widget" name="enable_widget" checked>
		<label class="form-check-label" for="enable_widget">Enable widget</label>
	</div>
	<div class="form-group form-check">
		<input type="checkbox" class="form-check-input" id="use_custom_class" name="use_custom_class">
		<label class="form-check-label" for="use_custom_class">Use custom class name to fix compatibility with old dolibase versions</label>
	</div>
	<input type="hidden" name="action" value="generate">
	<button type="submit" class="btn btn-primary">Generate</button>
</form>
