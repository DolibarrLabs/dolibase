
$(document).ready(function() {
	$('#name').on('keyup input', function() {
		var name = $(this).val();
		var nameToLower = name.toLowerCase();
		$('#description').val(name + 'Description');
		$('#rights_class').val(nameToLower);
		$('#folder').val(nameToLower.replace(/_/g, ''));
	});
});
