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
 * Generate document model
 */

$action = getPostData('action');
$message = array();

if ($action == 'generate')
{
	// Get data
	$model_name = sanitizeString(getPostData('model_name'));
	$data = array(
		'module_folder' => getPostData('module_folder'),
		'model_name' => $model_name, 
		'model_classname' => 'pdf_'.strtolower($model_name),
		'model_version' => getPostData('model_version'),
		'model_description' => getPostData('model_description')
	);

	// Check if doc model already exist
	$root = getDolibarrRootDirectory();
	$module_path = $root.'/custom/'.$data['module_folder'];
	$doc_model_path = $module_path.'/core/doc_models';
	$doc_model_filename = 'pdf_'.strtolower($model_name).'.modules.php';
	$doc_model_file = $doc_model_path.'/'.$doc_model_filename;

	if (file_exists($doc_model_file))
	{
		// Set error message
		$message = array(
			'text' => 'Document model <strong>'.$doc_model_file.'</strong> already exists.',
			'type' => 'danger'
		);
	}
	else
	{
		// Create doc models folder
		mkdir_r(array($doc_model_path), 0777);

		// Add doc model into module
		$template = getTemplate(__DIR__ . '/../tpl/model/doc_model.php', $data);
		file_put_contents($doc_model_file, $template);

		// Set files/folders permissions
		chmod_r($module_path, 0777, 0777);

		// Set success message
		$message = array(
			'text' => 'Document model <strong>'.$doc_model_file.'</strong> successfully generated.',
			'type' => 'success'
		);
	}
}

/**
 * Show view
 */

$options = array(
	'path_prefix' => '../',
	'title' => 'Model Builder',
	'navbar_active' => 'model/doc_model',
	'form_name' => 'model/doc_model',
	'css' => array(),
	'js' => array('doc_model.js'),
	'message' => $message,
	'modules_list' => getModulesList()
);

include_once '../views/layout.php';
