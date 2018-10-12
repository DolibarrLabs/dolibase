
$(document).ready(function() {
	$('#model_name').on('keyup input', function() {
		var sanitizedName = $(this).val().replace(/\s/g, '');
		$(this).val(sanitizedName);
		$('#const_name').val(sanitizedName.toUpperCase() + '_MASK');
	});
});
