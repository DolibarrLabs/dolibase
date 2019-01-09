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

// Load Dolibarr environment
if (false === (@include '../../../main.inc.php')) { // From htdocs directory (dolibase is in htdocs)
	if (false === (@include '../../../../main.inc.php')) { // From module directory (module is in htdocs)
		require '../../../../../main.inc.php'; // From "custom" directory (module is in custom directory)
	}
}

// Load related objects lib
require_once '../lib/related_objects.php';

top_httphead();

//echo '<!-- Ajax page called with url '.$_SERVER["PHP_SELF"].'?'.$_SERVER["QUERY_STRING"].' -->'."\n";

$action = GETPOST('action');

if ($action == 'search')
{
	$key = GETPOST('key');
	$result = search_object($key);

	echo json_encode($result);
}
