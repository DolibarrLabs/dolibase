
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="px-2">
	<div class="form-group">
		<label for="module_folder">Module</label>
		<select class="form-control" id="module_folder" name="module_folder" required>
			<?php foreach ($options['modules_list'] as $module) { ?>
				<option value="<?php echo $module; ?>"><?php echo $module; ?></option>
			<?php } ?>
		</select>
	</div>
	<div class="form-group">
		<label for="object_class">Object Class</label>
		<select class="form-control" id="object_class" name="object_class" aria-describedby="objectClassHelp">
			<?php foreach ($options['object_class_list'] as $object_class) { ?>
				<option value="<?php echo $object_class; ?>"><?php echo $object_class; ?></option>
			<?php } ?>
		</select>
		<small id="objectClassHelp" class="form-text text-muted">you can create a new object class from Model Builder</small>
	</div>
	<div class="form-group">
		<label for="page_name">Page Name</label>
		<input type="text" class="form-control" id="page_name" name="page_name" placeholder="card.php" value="card.php" required>
	</div>
	<div class="form-group">
		<label for="page_title">Page Title</label>
		<input type="text" class="form-control" id="page_title" name="page_title" placeholder="MyPageTitle" required>
	</div>
	<div class="form-group">
		<label for="access_perms">Access Permission(s)</label>
		<input type="text" class="form-control" id="access_perms" name="access_perms" placeholder="$user->rights->mymodule->read" value="<?php echo $options['access_perms']; ?>">
	</div>
	<div class="form-group">
		<label for="modify_perms">Modify Permission(s)</label>
		<input type="text" class="form-control" id="modify_perms" name="modify_perms" placeholder="$user->rights->mymodule->modify" value="<?php echo $options['modify_perms']; ?>">
	</div>
	<div class="form-group">
		<label for="delete_perms">Delete Permission(s)</label>
		<input type="text" class="form-control" id="delete_perms" name="delete_perms" placeholder="$user->rights->mymodule->delete" value="<?php echo $options['delete_perms']; ?>">
	</div>
	<div class="form-group form-check">
		<input type="checkbox" class="form-check-input" id="show_documents_block" name="show_documents_block">
		<label class="form-check-label" for="show_documents_block">Show documents block</label>
	</div>
	<input type="hidden" name="action" value="generate">
	<button type="submit" class="btn btn-primary">Generate</button>
</form>
