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

$(document).ready(function () {
	$('.dolibase_select').select2({
		dir: 'ltr',
		width: 'resolve',       /* off or resolve */
		minimumInputLength: 0
	});
});

<?php
