
$(document).ready(function() {
	function scrollTo(id) {
		$('html, body').animate({scrollTop: $(id).offset().top - 140}); // scroll to the id of the clicked link - height of the fixed nav
	}
	$(window).on('load hashchange', function() {
		if (location.hash) {
			scrollTo(location.hash);
		}
	});
	$('a[href^="#"]').on('click', function() {
		var id = $(this).attr('href');
		scrollTo(id);
	});
});
