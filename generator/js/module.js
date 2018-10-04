
$(document).ready(function() {
	$('#name').on('keyup input', function() {
		var name = $(this).val();
		var sanitizedName = name.replace(/\s/g, '').toLowerCase();
		$('#description').val(name + 'Description');
		$('#rights_class').val(sanitizedName);
		$('#folder').val(sanitizedName.replace(/_/g, ''));
	});
});
