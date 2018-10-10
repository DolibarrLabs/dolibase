
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="px-2">
	<?php foreach ($options['translation_strings'] as $key => $value) { ?>
		<div class="form-group">
			<label><?php echo $key; ?></label>
			<input type="hidden" name="translation_keys[]" value="<?php echo $key; ?>">
			<?php if (strlen($value) > 150) { ?>
				<textarea class="form-control" name="translation_values[]" placeholder="<?php echo htmlentities($value); ?>" required><?php echo htmlentities($value); ?></textarea>
			<?php } else { ?>
				<input type="text" class="form-control" name="translation_values[]" placeholder="<?php echo htmlentities($value); ?>" value="<?php echo htmlentities($value); ?>" required>
			<?php } ?>
		</div>
	<?php } ?>
	<?php foreach ($options['data'] as $key => $value) { ?>
		<input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>">
	<?php } ?>
	<input type="hidden" name="action" value="save">
	<button type="submit" id="save" class="btn btn-primary">Save</button>
</form>
