
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
		<label for="object_classname">Object Class Name</label>
		<input type="text" class="form-control" id="object_classname" name="object_classname" placeholder="MyClass" required>
	</div>
	<div class="form-group">
		<label for="object_element">Object Element</label>
		<input type="text" class="form-control" id="object_element" name="object_element" placeholder="myobject" required>
	</div>
	<div class="form-group">
		<label for="object_table">Object Table</label>
		<input type="text" class="form-control" id="object_table" name="object_table" aria-describedby="objectTableHelp" placeholder="mysqltable" required>
		<small id="objectTableHelp" class="form-text text-muted">without table prefix (llx_)</small>
	</div>
	<div class="form-group">
		<label for="pk_name">Primary Key Field Name</label>
		<input type="text" class="form-control" id="pk_name" name="pk_name" placeholder="rowid" value="rowid" required>
	</div>
	<div class="form-group">
		<label for="fetch_fields">Fetch Fields</label>
		<input type="text" class="form-control" id="fetch_fields" name="fetch_fields" aria-describedby="fetchFieldsHelp" placeholder="rowid, name, creation_date" required>
		<small id="fetchFieldsHelp" class="form-text text-muted">fields to always fetch when calling fetch method(s)</small>
	</div>
	<div class="form-group">
		<label for="date_fields">Date Fields</label>
		<select multiple class="form-control" id="date_fields" name="date_fields[]" aria-describedby="dateFieldsHelp">
		</select>
		<small id="dateFieldsHelp" class="form-text text-muted">date fields will be converted automatically to Dolibarr date format (use CTRL to select multiple fields)</small>
	</div>
	<div class="form-group">
		<label for="tooltip_title">Tooltip Title</label>
		<input type="text" class="form-control" id="tooltip_title" name="tooltip_title" placeholder="ShowMyObject">
	</div>
	<input type="hidden" name="action" value="generate">
	<button type="submit" class="btn btn-primary">Generate</button>
</form>
