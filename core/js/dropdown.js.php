<?php
/**
 * Dolibase
 * 
 * Open source framework for Dolibarr ERP/CRM
 *
 * Copyright (c) 2018 - 2019
 *
 *
 * @package     Dolibase
 * @author      AXeL
 * @copyright   Copyright (c) 2018 - 2019, AXeL-dev
 * @license     MIT
 * @link        https://github.com/AXeL-dev/dolibase
 * 
 */

header('Content-Type: application/javascript');

?>

$(document).ready(function() {

	$(document).click(function() {
		$('.dropdown-click .dropdown-content').removeClass('show');
	});

	$('.drop-btn').click(function(e) {
		e.stopPropagation();
		$('.dropdown-click .dropdown-content').removeClass('show');
		$(this).next().addClass('show');
	});
});

<?php
