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

// function to read file in client side
function readFile(file, callback)
{
	if (window.File && window.FileReader && window.FileList && window.Blob)
	{
		var reader = new FileReader();
		reader.onload = function(e) {
			var filecontent = e.target.result;
			callback(filecontent);
		}
		reader.readAsText(file);
	}
	else
	{
		if (window.XMLHttpRequest)
		{// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		}
		else
		{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.open("GET",file,false);
		xmlhttp.send();
		xmlDoc=xmlhttp.responseText;

		var filecontent = xmlDoc;

		callback(filecontent);
	}
}

<?php
