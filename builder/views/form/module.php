
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
	<ul class="nav nav-tabs" role="tablist">
		<li class="nav-item">
			<a class="nav-link active" data-toggle="tab" href="#module" role="tab" aria-controls="module" aria-selected="true">Module</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-toggle="tab" href="#author" role="tab" aria-controls="author" aria-selected="false">Author</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-toggle="tab" href="#num_model" role="tab" aria-controls="num_model" aria-selected="false">Numbering Model</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-toggle="tab" href="#menus" role="tab" aria-controls="menus" aria-selected="false">Menus</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-toggle="tab" href="#permissions" role="tab" aria-controls="permissions" aria-selected="false">Permissions</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-toggle="tab" href="#admin_pages" role="tab" aria-controls="admin_pages" aria-selected="false">Admin Pages</a>
		</li>
	</ul>

	<div class="tab-content p-3">
		<div class="tab-pane fade show active" id="module" role="tabpanel" aria-labelledby="module-tab">
			<div class="form-group">
				<label for="name">Name</label>
				<input type="text" class="form-control" id="name" name="name" placeholder="MyModule" required>
			</div>
			<div class="form-group">
				<label for="description">Description</label>
				<input type="text" class="form-control" id="description" name="description" placeholder="MyModuleDescription" required>
			</div>
			<div class="form-group">
				<label for="version">Version</label>
				<input type="text" class="form-control" id="version" name="version" aria-describedby="versionHelp" placeholder="1.0.0" value="1.0.0" required>
				<small id="versionHelp" class="form-text text-muted">possible values: 'development', 'experimental', 'dolibarr' or version</small>
			</div>
			<div class="form-group">
				<label for="number">Number</label>
				<input type="number" class="form-control" id="number" name="number" aria-describedby="numberHelp" placeholder="110000" min="1" value="<?php echo rand(500, 1000).'000'; ?>" required>
				<small id="numberHelp" class="form-text text-muted">avoid small numbers because they are used for core modules</small>
			</div>
			<div class="form-group">
				<label for="family">Family</label>
				<select class="form-control" id="family" name="family">
					<option value="hr">Human Resources Management (HR)</option>
					<option value="crm">Customer Relations Management (CRM)</option>
					<option value="srm">Supplier Relation Management (SRM)</option>
					<option value="financial">Financial Modules (Accounting/Treasury)</option>
					<option value="products">Product Management (PM)</option>
					<option value="projects">Projects/Collaborative work</option>
					<option value="ecm">Electronic Content Management (ECM)</option>
					<option value="technic">Multi-module tools</option>
					<option value="portal">Web sites and other frontal application</option>
					<option value="interface">Interfaces with external systems</option>
					<option value="base">System</option>
					<option value="other" selected>Other</option>
				</select>
			</div>
			<div class="form-group">
				<label for="position">Position</label>
				<input type="number" class="form-control" id="position" name="position" placeholder="500" min="1" value="500" required>
			</div>
			<div class="form-group">
				<label for="rights_class">Rights Class</label>
				<input type="text" class="form-control" id="rights_class" name="rights_class" aria-describedby="rightsClassHelp" placeholder="my_module" required>
				<small id="rightsClassHelp" class="form-text text-muted">key to reference module (for permissions, menus, etc.)</small>
			</div>
			<div class="form-group">
				<label for="url">Dolistore Url</label>
				<input type="text" class="form-control" id="url" name="url" placeholder="https://www.dolistore.com/my_module" value="#">
			</div>
			<div class="form-group">
				<label for="folder">Folder</label>
				<input type="text" class="form-control" id="folder" name="folder" aria-describedby="folderHelp" placeholder="mymodule" required>
				<small id="folderHelp" class="form-text text-muted">advice: never use underscores in module folder name to avoid many problems especially with top menu icon, module widgets etc..</small>
			</div>
			<div class="form-group">
				<label for="picture">Picture</label>
				<input type="file" accept="image/*" class="form-control-file" id="picture" name="picture" aria-describedby="pictureHelp" required>
				<small id="pictureHelp" class="form-text text-muted">preferred size (128 x 128)</small>
			</div>
			<div class="form-group form-check">
				<input type="checkbox" class="form-check-input" id="check_updates" name="check_updates" checked>
				<label class="form-check-label" for="check_updates">Enable check for module updates</label>
			</div>
			<div class="form-group form-check">
				<input type="checkbox" class="form-check-input" id="enable_logs" name="enable_logs">
				<label class="form-check-label" for="enable_logs">Enable logs</label>
			</div>
			<div class="form-group form-check">
				<input type="checkbox" class="form-check-input" id="enable_triggers" name="enable_triggers">
				<label class="form-check-label" for="enable_triggers">Enable triggers</label>
			</div>
			<div class="form-group form-check">
				<input type="checkbox" class="form-check-input" id="enable_for_external_users" name="enable_for_external_users">
				<label class="form-check-label" for="enable_for_external_users">Enable for external users</label>
			</div>
			<div class="form-group form-check">
				<input type="checkbox" class="form-check-input" id="use_custom_class" name="use_custom_class">
				<label class="form-check-label" for="use_custom_class">Use custom class name to fix compatibility with old dolibase versions</label>
			</div>
		</div>

		<div class="tab-pane fade" id="author" role="tabpanel" aria-labelledby="author-tab">
			<div class="form-group">
				<label for="author_name">Name</label>
				<input type="text" class="form-control" id="author_name" name="author_name" aria-describedby="authorHelp" placeholder="YourName" value="<?php echo (isset($options['author_info']['name']) ? $options['author_info']['name'] : ''); ?>" required>
				<small id="authorHelp" class="form-text text-muted">author default informations can be changed in author.json file</small>
			</div>
			<div class="form-group">
				<label for="author_url">Url</label>
				<input type="text" class="form-control" id="author_url" name="author_url" placeholder="#" value="<?php echo (isset($options['author_info']['url']) ? $options['author_info']['url'] : ''); ?>">
			</div>
			<div class="form-group">
				<label for="author_email">Email</label>
				<input type="email" class="form-control" id="author_email" name="author_email" placeholder="email@provider.com" value="<?php echo (isset($options['author_info']['email']) ? $options['author_info']['email'] : ''); ?>">
			</div>
			<div class="form-group">
				<label for="author_dolistore_url">Dolistore Url</label>
				<input type="text" class="form-control" id="author_dolistore_url" name="author_dolistore_url" placeholder="#" value="<?php echo (isset($options['author_info']['dolistore_url']) ? $options['author_info']['dolistore_url'] : ''); ?>">
			</div>
		</div>

		<div class="tab-pane fade" id="num_model" role="tabpanel" aria-labelledby="num_model-tab">
			<div class="form-group">
				<label for="num_model_table">Table Name</label>
				<input type="text" class="form-control" id="num_model_table" name="num_model_table" aria-describedby="numModelTableHelp" placeholder="mysqltable">
				<small id="numModelTableHelp" class="form-text text-muted">without table prefix (llx_)</small>
			</div>
			<div class="form-group">
				<label for="num_model_field">Table Field</label>
				<input type="text" class="form-control" id="num_model_field" name="num_model_field" aria-describedby="numModelFieldHelp" placeholder="ref">
				<small id="numModelFieldHelp" class="form-text text-muted">table field that will be associated with the default numbering models of dolibase</small>
			</div>
			<div class="form-group">
				<label for="num_model_prefix">Model Prefix</label>
				<input type="text" class="form-control" id="num_model_prefix" name="num_model_prefix" aria-describedby="numModelPrefixHelp" placeholder="PR">
				<small id="numModelPrefixHelp" class="form-text text-muted">prefix used in marbre numbering model</small>
			</div>
		</div>

		<div class="tab-pane fade" id="menus" role="tabpanel" aria-labelledby="menus-tab">
			<div class="form-group form-check">
				<input type="checkbox" class="form-check-input" id="add_top_menu" name="add_top_menu">
				<label class="form-check-label" for="add_top_menu">Add top menu</label>
			</div>
			<div class="form-group form-check">
				<input type="checkbox" class="form-check-input" id="add_generic_left_menu" name="add_generic_left_menu">
				<label class="form-check-label" for="add_generic_left_menu" aria-describedby="addGenericLeftMenuHelp">Add generic left menu</label>
				<small id="addGenericLeftMenuHelp" class="form-text text-muted">adds left menu entries for index/create/list pages</small>
			</div>
		</div>

		<div class="tab-pane fade" id="permissions" role="tabpanel" aria-labelledby="permissions-tab">
			<div class="form-group form-check">
				<input type="checkbox" class="form-check-input" id="add_crud_perms" name="add_crud_perms">
				<label class="form-check-label" for="add_crud_perms" aria-describedby="addCrudPermsHelp">Add CRUD permissions</label>
				<small id="addCrudPermsHelp" class="form-text text-muted">adds 4 permissions for Create/Read/Update/Delete actions</small>
			</div>
		</div>

		<div class="tab-pane fade" id="admin_pages" role="tabpanel" aria-labelledby="admin_pages-tab">
			<div class="form-group">
				<div class="form-check mb-2">
					<input type="checkbox" class="form-check-input" id="add_setup_page" name="add_setup_page" checked disabled>
					<label class="form-check-label" for="add_setup_page">Add setup page</label>
				</div>
				<div class="form-check ml-4 mb-1">
					<input type="checkbox" class="form-check-input" id="add_num_models_settings" name="add_num_models_settings">
					<label class="form-check-label" for="add_num_models_settings">Add numbering models settings</label>
				</div>
				<div class="form-check ml-4">
					<input type="checkbox" class="form-check-input" id="add_doc_models_settings" name="add_doc_models_settings">
					<label class="form-check-label" for="add_doc_models_settings">Add document models settings</label>
				</div>
			</div>
			<div class="form-group form-check">
				<input type="checkbox" class="form-check-input" id="add_about_page" name="add_about_page" checked disabled>
				<label class="form-check-label" for="add_about_page">Add about page</label>
			</div>
			<div class="form-group form-check">
				<input type="checkbox" class="form-check-input" id="add_extrafields_page" name="add_extrafields_page">
				<label class="form-check-label" for="add_extrafields_page" aria-describedby="addExtrafieldsPageHelp">Add extrafields page</label>
				<small id="addExtrafieldsPageHelp" class="form-text text-muted">adds also an extrafields table into sql folder</small>
			</div>
			<div class="form-group form-check">
				<input type="checkbox" class="form-check-input" id="add_changelog_page" name="add_changelog_page" checked>
				<label class="form-check-label" for="add_changelog_page" aria-describedby="addChangelogPageHelp">Add changelog page</label>
				<small id="addChangelogPageHelp" class="form-text text-muted">adds also a changelog.json file into module folder</small>
			</div>
		</div>

		<input type="hidden" name="action" value="generate">
		<button type="submit" class="btn btn-primary">Generate</button>
	</div>
</form>
