
$(document).ready(function() {
	$('#name').on('keyup input', function() {
		var name = $(this).val();
		var sanitizedName = name.replace(/\s/g, '').toLowerCase();
		$('#description').val(name + 'Description');
		$('#rights_class').val(sanitizedName);
		$('#folder').val(sanitizedName.replace(/_/g, ''));
	});

	$('button[type="submit"]').on('click', function() {
		$(':input[required]').each(function() {
			if ($(this).val() == '') {
				var tabId = $(this).parents('div[class*="tab-pane"]').attr('id');
				$('.nav-tabs a[href="#' + tabId + '"]').tab('show');
				return false;
			}
		});
	});
});
