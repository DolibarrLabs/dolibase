
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
		<label for="lang_folder">Language Folder</label>
		<select class="form-control" id="lang_folder" name="lang_folder" aria-describedby="langFolderHelp" required>
			<?php foreach ($options['lang_folder_list'] as $lang_folder) { ?>
				<option value="<?php echo $lang_folder; ?>"><?php echo $lang_folder; ?></option>
			<?php } ?>
		</select>
		<small id="langFolderHelp" class="form-text text-muted">original language folder from which to get language files</small>
	</div>
	<div class="form-group">
		<label for="lang_file">Language File</label>
		<select class="form-control" id="lang_file" name="lang_file" aria-describedby="langFileHelp" required>
			<?php foreach ($options['lang_file_list'] as $lang_file) { ?>
				<option value="<?php echo $lang_file; ?>"><?php echo $lang_file; ?></option>
			<?php } ?>
		</select>
		<small id="langFileHelp" class="form-text text-muted">language file from which to get translation strings</small>
	</div>
	<div class="form-group">
		<label for="author_name">Author Name</label>
		<input type="text" class="form-control" id="author_name" name="author_name" placeholder="YourName" value="<?php echo (isset($options['author_info']['name']) ? $options['author_info']['name'] : ''); ?>" required>
	</div>
	<div class="form-group">
		<label for="translation_folder_name">Translation Folder Name</label>
		<input type="text" class="form-control" id="translation_folder_name" name="translation_folder_name" aria-describedby="translationHelp" placeholder="fr_FR" required>
		<small id="translationHelp" class="form-text text-muted">examples: ar_AR, fr_FR, en_US, en_GB, es_ES, de_DE, it_IT</small>
	</div>
	<input type="hidden" name="action" value="generate">
	<button type="submit" class="btn btn-primary">Generate</button>
</form>
