
<?php if (isset($options['links']) && ! empty($options['links'])) { ?>
	<?php foreach ($options['links'] as $link) { ?>
		<a href="<?php echo $link['href']; ?>" class="<?php echo $link['class']; ?>"><?php echo $link['text']; ?></a>
	<?php } ?>
<?php } else { ?>
	<div class="card tab-card">
		<div class="card-header tab-card-header">
			<ul class="nav nav-tabs card-header-tabs" role="tablist">
				<li class="nav-item">
					<a class="nav-link<?php echo ! isset($options['tabs_active']) || empty($options['tabs_active']) ? ' active' : ''; ?>" id="new-tab" data-toggle="tab" href="#new" role="tab" aria-controls="New" aria-selected="true">New</a>
				</li>
				<li class="nav-item">
					<a class="nav-link<?php echo isset($options['tabs_active']) && $options['tabs_active'] == 'list' ? ' active' : ''; ?>" id="list-tab" data-toggle="tab" href="#list" role="tab" aria-controls="Packages" aria-selected="false">Packages<?php echo is_array($options['packages_list']) && ! empty($options['packages_list']) ? ' <span class="badge badge-primary">'.count($options['packages_list']).'</span>' : ''; ?></a>
				</li>
			</ul>
		</div>

		<div class="tab-content p-3">
			<div class="tab-pane fade<?php echo ! isset($options['tabs_active']) || empty($options['tabs_active']) ? ' show active' : ''; ?>" id="new" role="tabpanel" aria-labelledby="new-tab">
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="px-2">
					<div class="form-group">
						<label for="module_folder">Module</label>
						<select class="form-control" id="module_folder" name="module_folder" required>
							<?php foreach ($options['modules_list'] as $module) { ?>
								<option value="<?php echo $module; ?>"><?php echo $module; ?></option>
							<?php } ?>
						</select>
					</div>
					<input type="hidden" name="action" value="generate">
					<button type="submit" class="btn btn-primary">Generate</button>
				</form>
			</div>

			<div class="tab-pane fade<?php echo isset($options['tabs_active']) && $options['tabs_active'] == 'list' ? ' show active' : ''; ?>" id="list" role="tabpanel" aria-labelledby="list-tab">
				<div class="table-responsive">
					<table class="table">
						<thead>
							<tr>
								<th scope="col">Package</th>
								<th scope="col">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($options['packages_list'] as $package_name) { ?>
								<tr<?php echo isset($options['packages_active']) && $options['packages_active'] == $package_name ? ' class="table-warning"' : ''; ?>>
									<td><?php echo $package_name; ?></td>
									<td>
										<a class="btn btn-danger mb-1" title="Delete" href="<?php echo $_SERVER['PHP_SELF'].'?action=delete&package_name='.$package_name; ?>"><i class="fa fa-trash"></i></a>
										<a class="btn btn-success mb-1" title="Download" href="<?php echo $options['packages_url'].'/'.$package_name; ?>"><i class="fa fa-download"></i></a>
									</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
