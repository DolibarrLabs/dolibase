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

include_once '../lib/functions.php';

/**
 * Handle ajax requests
 */

// Get request parameters
$action = getPostData('action');
$module_folder = getPostData('module_folder');

if ($action == 'get_rights_class')
{
	// Get module rights class
	echo getModuleRightsClass($module_folder);
}
else if ($action == 'get_object_class_html')
{
	// Get module object class list
	$list = getModuleObjectClassList($module_folder);

	// Generate HTML
	foreach ($list as $object_class) {
		echo '<option value="'.$object_class.'">'.$object_class.'</option>';
	}
}
