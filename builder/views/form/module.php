
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
	<ul class="nav nav-tabs" role="tablist">
		<li class="nav-item">
			<a class="nav-link active" data-toggle="tab" href="#module" role="tab" aria-controls="module" aria-selected="true">Module</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-toggle="tab" href="#author" role="tab" aria-controls="author" aria-selected="false">Author</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-toggle="tab" href="#menus" role="tab" aria-controls="menus" aria-selected="false">Menus</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-toggle="tab" href="#permissions" role="tab" aria-controls="permissions" aria-selected="false">Permissions</a>
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
				<input type="checkbox" class="form-check-input" id="check_updates" name="check_updates">
				<label class="form-check-label" for="check_updates">Enable check for module updates</label>
			</div>
			<div class="form-group form-check">
				<input type="checkbox" class="form-check-input" id="enable_logs" name="enable_logs">
				<label class="form-check-label" for="enable_logs">Enable logs</label>
			</div>
			<div class="form-group form-check">
				<input type="checkbox" class="form-check-input" id="use_custom_class" name="use_custom_class" checked>
				<label class="form-check-label" for="use_custom_class">Use custom class name to fix compatibility with old dolibase versions</label>
			</div>
		</div>

		<div class="tab-pane fade" id="author" role="tabpanel" aria-labelledby="author-tab">
			<div class="form-group">
				<label for="author_name">Name</label>
				<input type="text" class="form-control" id="author_name" name="author_name" placeholder="YourName" value="<?php echo (isset($options['author_info']['name']) ? $options['author_info']['name'] : ''); ?>" required>
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

		<div class="tab-pane fade" id="menus" role="tabpanel" aria-labelledby="menus-tab">
			<div class="form-group form-check">
				<input type="checkbox" class="form-check-input" id="add_top_menu" name="add_top_menu">
				<label class="form-check-label" for="add_top_menu">Add top menu</label>
			</div>
			<div class="form-group form-check">
				<input type="checkbox" class="form-check-input" id="add_generic_left_menu" name="add_generic_left_menu">
				<label class="form-check-label" for="add_generic_left_menu" aria-describedby="AddGenericLeftMenuHelp">Add generic left menu</label>
				<small id="AddGenericLeftMenuHelp" class="form-text text-muted">adds left menu entries for index/create/list pages</small>
			</div>
		</div>

		<div class="tab-pane fade" id="permissions" role="tabpanel" aria-labelledby="permissions-tab">
			<div class="form-group form-check">
				<input type="checkbox" class="form-check-input" id="add_crud_perms" name="add_crud_perms">
				<label class="form-check-label" for="add_crud_perms" aria-describedby="AddCrudPermsHelp">Add CRUD permissions</label>
				<small id="AddCrudPermsHelp" class="form-text text-muted">adds 4 permissions for Create/Read/Update/Delete actions</small>
			</div>
		</div>

		<input type="hidden" name="action" value="generate">
		<button type="submit" class="btn btn-primary">Generate</button>
	</div>
</form>