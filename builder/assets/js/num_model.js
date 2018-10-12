
$(document).ready(function() {
	$('#model_name').on('keyup input', function() {
		var sanitizedClassName = $(this).val().replace(/\s/g, '');
		$(this).val(sanitizedClassName);
		$('#const_name').val(sanitizedClassName.toUpperCase() + '_MASK');
	});
});
