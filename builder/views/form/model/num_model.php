
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
		<input type="text" class="form-control" id="model_name" name="model_name" placeholder="MyNumModel" required>
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
		<input type="text" class="form-control" id="model_description" name="model_description" placeholder="GenericNumRefModelDesc" value="GenericNumRefModelDesc" required>
	</div>
	<div class="form-group">
		<label for="const_name">Constant Name</label>
		<input type="text" class="form-control" id="const_name" name="const_name" aria-describedby="constNameHelp" placeholder="MY_NUM_MODEL_MASK" required>
		<small id="constNameHelp" class="form-text text-muted">used to store model mask</small>
	</div>
	<div class="form-group">
		<label for="table_name">Table Name</label>
		<input type="text" class="form-control" id="table_name" name="table_name" aria-describedby="tableNameHelp" placeholder="mysqltable" required>
		<small id="tableNameHelp" class="form-text text-muted">without table prefix (llx_)</small>
	</div>
	<div class="form-group">
		<label for="field_name">Field Name</label>
		<input type="text" class="form-control" id="field_name" name="field_name" aria-describedby="fieldNameHelp" placeholder="ref" value="ref" required>
		<small id="fieldNameHelp" class="form-text text-muted">table field that will be associated with the numbering model</small>
	</div>
	<input type="hidden" name="action" value="generate">
	<button type="submit" class="btn btn-primary">Generate</button>
</form>
