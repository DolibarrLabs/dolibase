
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
	<div class="accordion form-group" id="accordion">
		<div class="card">
			<div class="card-header" id="headingOne">
				<h5 class="mb-0">
					<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Module</button>
				</h5>
			</div>

			<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
				<div class="card-body">
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
						<input type="number" class="form-control" id="position" name="position" placeholder="500" min="1" value="500">
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
			</div>
		</div>
		<div class="card">
			<div class="card-header" id="headingTwo">
				<h5 class="mb-0">
					<button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">Author</button>
				</h5>
			</div>

			<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
				<div class="card-body">
					<div class="form-group">
						<label for="author_name">Name</label>
						<input type="text" class="form-control" id="author_name" name="author_name" placeholder="YourName" value="AXeL" required>
					</div>
					<div class="form-group">
						<label for="author_url">Url</label>
						<input type="text" class="form-control" id="author_url" name="author_url" placeholder="#" value="https://github.com/AXeL-dev">
					</div>
					<div class="form-group">
						<label for="author_email">Email</label>
						<input type="email" class="form-control" id="author_email" name="author_email" placeholder="email@provider.com" value="anass_denna@hotmail.fr" required>
					</div>
					<div class="form-group">
						<label for="author_dolistore_url">Dolistore Url</label>
						<input type="text" class="form-control" id="author_dolistore_url" name="author_dolistore_url" placeholder="#" value="https://www.dolistore.com/en/search?orderby=position&orderway=desc&search_query=axel">
					</div>
				</div>
			</div>
		</div>
	</div>
	<input type="hidden" name="action" value="generate">
	<button type="submit" class="btn btn-primary">Generate</button>
</form>
