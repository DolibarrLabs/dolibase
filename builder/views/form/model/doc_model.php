
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
		<label for="model_name">Model Name</label>
		<input type="text" class="form-control" id="model_name" name="model_name" placeholder="MyDocModel" required>
	</div>
	<div class="form-group">
		<label for="model_version">Model Version</label>
		<select class="form-control" id="model_version" name="model_version" required>
			<option value="dolibarr">dolibarr</option>
			<option value="development">development</option>
			<option value="experimental">experimental</option>
		</select>
	</div>
	<div class="form-group">
		<label for="model_description">Model Description</label>
		<input type="text" class="form-control" id="model_description" name="model_description" placeholder="MyDocModelDescription" required>
	</div>
	<input type="hidden" name="action" value="generate">
	<button type="submit" class="btn btn-primary">Generate</button>
</form>
