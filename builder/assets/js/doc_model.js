
$(document).ready(function() {
	$('#model_name').on('keyup input', function() {
		var sanitizedName = $(this).val().replace(/\s/g, '');
		$(this).val(sanitizedName);
		$('#model_description').val(sanitizedName + 'Description');
	});
});
