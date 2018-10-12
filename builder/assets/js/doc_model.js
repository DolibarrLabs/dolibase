
$(document).ready(function() {
	$('#model_name').on('keyup input', function() {
		var sanitizedClassName = $(this).val().replace(/\s/g, '');
		$(this).val(sanitizedClassName);
		$('#model_description').val(sanitizedClassName + 'Description');
	});
});
