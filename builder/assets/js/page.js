
$(document).ready(function() {
	$('#module_folder').on('change', function() {
		// Rights class request
		$.post('../ajax/page.php', {
			action: 'get_rights_class',
			module_folder: $(this).val()
		}, function(response, status) {
			console.log('Response: ' + response + '\nStatus: ' + status);
			if (status == 'success') {
				$('#access_perms').val('$user->rights->' + response + '->read');
				$('#access_perms_create_page').val('$user->rights->' + response + '->create'); // will apply only on create page
				// modify & delete perms will apply only on card page
				$('#modify_perms').val('$user->rights->' + response + '->modify');
				$('#delete_perms').val('$user->rights->' + response + '->delete');
			}
		});

		// Object class request
		$.post('../ajax/page.php', {
			action: 'get_object_class_html',
			module_folder: $(this).val()
		}, function(response, status) {
			console.log('Response: ' + response + '\nStatus: ' + status);
			if (status == 'success') {
				$('#object_class').html(response);
			}
		});
	});
});
