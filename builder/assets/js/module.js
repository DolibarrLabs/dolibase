
$(document).ready(function() {
	$('#name').on('keyup input', function() {
		var name = $(this).val();
		var sanitizedName = name.replace(/\s/g, '').toLowerCase();
		$('#description').val(name + 'Description');
		$('#rights_class').val(sanitizedName);
		$('#folder').val(sanitizedName.replace(/_/g, ''));
	});

	$('form input').on('invalid', function() {
		alert($("label[for='" + $(this).attr('id') + "']").text() + ' field is required!');
	});
});
