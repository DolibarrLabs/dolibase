
<?php if (isset($options['links']) && ! empty($options['links'])) { ?>
	<?php foreach ($options['links'] as $link) { ?>
		<a href="<?php echo $link['href']; ?>" class="<?php echo $link['class']; ?>"><?php echo $link['text']; ?></a>
	<?php } ?>
<?php } else { ?>
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
<?php } ?>
