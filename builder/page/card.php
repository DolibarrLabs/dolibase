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
 * Generate page
 */

$action = getPostData('action');
$message = array();

if ($action == 'generate')
{
	// Get data
	$page_name = getPostData('page_name');
	$data = array(
		'module_folder' => getPostData('module_folder'),
		'page_name' => $page_name,
		'page_title' => getPostData('page_title'),
		'access_perms' => getPostData('access_perms'),
		'modify_perms' => getPostData('modify_perms'),
		'delete_perms' => getPostData('delete_perms'),
		'show_documents_block' => bool2Alpha(getPostData('show_documents_block'))
	);

	// Check if page already exist
	$root = getDolibarrRootDirectory();
	$module_path = $root.'/custom/'.$data['module_folder'];
	$page_file = $module_path.'/'.$page_name;

	if (file_exists($page_file))
	{
		// Set error message
		$message = array(
			'text' => 'Page <strong>'.$page_file.'</strong> already exists.',
			'type' => 'danger'
		);
	}
	else
	{
		// Add page into module
		$template = getTemplate(__DIR__ . '/../tpl/page/card.php', $data);
		file_put_contents($page_file, $template);

		// Set file permission
		chmod($page_file, 0777);

		// Set success message
		$message = array(
			'text' => 'Page <strong>'.$page_file.'</strong> successfully generated.',
			'type' => 'success'
		);
	}
}

/**
 * Show view
 */

$options = array(
	'path_prefix' => '../',
	'title' => 'Page Builder',
	'navbar_active' => 'page/card',
	'form_name' => 'page/card',
	'css' => array(),
	'js' => array(),
	'message' => $message,
	'modules_list' => getModulesList()
);

include_once '../views/layout.php';