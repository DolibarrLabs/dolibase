
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
		<input type="text" class="form-control" id="page_name" name="page_name" placeholder="stats.php" value="stats.php" required>
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
		<label for="date_field">Date Field</label>
		<input type="text" class="form-control" id="date_field" name="date_field" aria-describedby="dateFieldHelp" placeholder="creation_date" required>
		<small id="dateFieldHelp" class="form-text text-muted">object date field (used to generate statistics graphs)</small>
	</div>
	<div class="form-group">
		<label for="amount_field">Amount Field</label>
		<input type="text" class="form-control" id="amount_field" name="amount_field" aria-describedby="amountFieldHelp" placeholder="price" required>
		<small id="amountFieldHelp" class="form-text text-muted">object amount field (also used to generate statistics graphs)</small>
	</div>
	<input type="hidden" name="action" value="generate">
	<button type="submit" class="btn btn-primary">Generate</button>
</form>
