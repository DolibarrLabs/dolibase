
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

	$('#use_lite_dolibase').on('change', function() {
		if ($(this).is(':checked')) {
			$('a[href="#num_model_tab"]').parent('li').hide();
			$('#add_num_models_settings, #add_doc_models_settings, #add_extrafields_page').attr('disabled', true).parent().hide();
		}
		else {
			$('a[href="#num_model_tab"]').parent('li').show();
			$('#add_num_models_settings, #add_doc_models_settings, #add_extrafields_page').removeAttr('disabled').parent().show();
		}
	});
});
