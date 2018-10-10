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

if ($action == 'get_lang_folder_html')
{
	// Get lang folders list
	$list = getModuleLangList($module_folder);

	// Generate HTML
	foreach ($list as $lang_folder) {
		echo '<option value="'.$lang_folder.'">'.$lang_folder.'</option>';
	}
}
else if ($action == 'get_lang_file_html')
{
	// Get additional parameters
	$lang_folder = getPostData('lang_folder');

	// Get lang files list
	$list = getModuleLangFileList($module_folder, $lang_folder);

	// Generate HTML
	foreach ($list as $lang_file) {
		echo '<option value="'.$lang_file.'">'.$lang_file.'</option>';
	}
}
