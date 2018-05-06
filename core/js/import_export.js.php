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

// function to download file in client side
function downloadFile(self, filename, data, type, type2)
{
    try
    {
        blob = new Blob([data], {type: type});
        saveAs(blob, filename);
    }
    catch (e)
    {
        // Deliberate 'false', see comment below
        if (false && window.navigator.msSaveBlob) {
        	var blob = new Blob([decodeURIComponent(data)], {type: type});

			// Crashes in IE 10, IE 11 and Microsoft Edge
			// See MS Edge Issue #10396033
			// Hence, the deliberate 'false'
			// This is here just for completeness
			// Remove the 'false' at your own risk
			window.navigator.msSaveBlob(blob, filename);

		} else if (window.Blob && window.URL) {
			// HTML5 Blob
			var blob = new Blob([data], {type: type});
			var url = URL.createObjectURL(blob);

			$(self).attr({
				'download': filename,
				'href': url
			});
		} else {
			// Data URI
			var newData = type2 + encodeURIComponent(data);

			$(self).attr({
				'download': filename,
				'href': newData,
				'target': '_blank'
			});
		}
    }
}

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
