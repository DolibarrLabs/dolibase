
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="px-2">
	<div class="form-group">
		<label for="module_folder">Module</label>
		<select class="form-control" id="module_folder" name="module_folder">
			<?php foreach ($options['modules_list'] as $module) { ?>
				<option value="<?php echo $module; ?>"><?php echo $module; ?></option>
			<?php } ?>
		</select>
	</div>
	<div class="form-group">
		<label for="page_name">Page Name</label>
		<input type="text" class="form-control" id="page_name" name="page_name" placeholder="new.php" value="new.php" required>
	</div>
	<div class="form-group">
		<label for="page_title">Page Title</label>
		<input type="text" class="form-control" id="page_title" name="page_title" placeholder="MyPageTitle" required>
	</div>
	<div class="form-group">
		<label for="access_perms">Access Permission(s)</label>
		<input type="text" class="form-control" id="access_perms" name="access_perms" placeholder="$user->rights->mymodule->create">
	</div>
	<input type="hidden" name="action" value="generate">
	<button type="submit" class="btn btn-primary">Generate</button>
</form>
