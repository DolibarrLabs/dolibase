
$(document).ready(function() {
	$('#module_folder').on('change', function() {
		// Lang folders request
		$.post('ajax/translation.php', {
			action: 'get_lang_folder_html',
			module_folder: $(this).val()
		}, function(response, status) {
			console.log('Response: ' + response + '\nStatus: ' + status);
			if (status == 'success') {
				$('#lang_folder').html(response);
				// Lang files request
				$.post('ajax/translation.php', {
					action: 'get_lang_file_html',
					module_folder: $('#module_folder').val(),
					lang_folder: $('#lang_folder').val()
				}, function(response, status) {
					console.log('Response: ' + response + '\nStatus: ' + status);
					if (status == 'success') {
						$('#lang_file').html(response);
					}
				});
			}
		});
	});

	$('#lang_folder').on('change', function() {
		// Lang files request
		$.post('ajax/translation.php', {
			action: 'get_lang_file_html',
			module_folder: $('#module_folder').val(),
			lang_folder: $(this).val()
		}, function(response, status) {
			console.log('Response: ' + response + '\nStatus: ' + status);
			if (status == 'success') {
				$('#lang_file').html(response);
			}
		});
	});
});
