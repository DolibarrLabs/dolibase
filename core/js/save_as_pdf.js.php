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
 * @copyright	Copyright (c) 2018 - 2019, AXeL-dev
 * @license
 * @link
 * 
 */

header('Content-Type: application/javascript');

?>

$(document).ready(function() {

	// Save as PDF
	$("#save_as_pdf").on('click', function(event) {
		var table = $('table');
		var filename = $(document).find('title').text() + '.pdf';
		var doc = new jsPDF('p', 'pt'); // 'p' for a vertical orientation & 'l' for an horizontal orientation
		var res = doc.autoTableHtmlToJson(table.get(0));
		$('div.pagination').hide();
		doc.autoTable(res.columns, res.data, {
			theme: 'plain',
			styles: {
				fontSize: 8,
				overflow: 'linebreak'
			}
	    });
	    $('div.pagination').show();
		doc.save(filename);
	});
});

<?php
