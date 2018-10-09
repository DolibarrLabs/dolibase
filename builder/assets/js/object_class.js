
$(document).ready(function() {
	$('#object_classname').on('keyup input', function() {
		var sanitizedClassName = $(this).val().replace(/\s/g, '');
		$(this).val(sanitizedClassName);
		$('#object_element').val(sanitizedClassName.toLowerCase());
	});

	$('#fetch_fields').on('keyup input', function() {
		var html = '';
		if ($.trim($(this).val()).length > 0) {
			$.each($(this).val().split(','), function(index, value) {
				var sanitizedValue = value.replace(/[\s'"]/g, '');
				html += '<option value="' + sanitizedValue + '">' + sanitizedValue + '</option>';
			});
		}
		$('#date_fields').html(html);
	});
});
