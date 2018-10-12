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
 * Generate numbering model
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
		'model_classname' => 'NumModel'.ucfirst($model_name), 
		'model_version' => getPostData('model_version'),
		'model_description' => getPostData('model_description'),
		'const_name' => getPostData('const_name'),
		'table_name' => getPostData('table_name'),
		'field_name' => getPostData('field_name')
	);

	// Check if num model already exist
	$root = getDolibarrRootDirectory();
	$module_path = $root.'/custom/'.$data['module_folder'];
	$num_model_path = $module_path.'/core/num_models';
	$num_model_filename = strtolower($model_name).'.php';
	$num_model_file = $num_model_path.'/'.$num_model_filename;

	if (file_exists($num_model_file))
	{
		// Set error message
		$message = array(
			'text' => 'Numbering model <strong>'.$num_model_file.'</strong> already exists.',
			'type' => 'danger'
		);
	}
	else
	{
		// Create num models folder
		mkdir_r(array($num_model_path), 0777);

		// Add num model into module
		$template = getTemplate(__DIR__ . '/../tpl/model/num_model.php', $data);
		file_put_contents($num_model_file, $template);

		// Set files/folders permissions
		chmod_r($module_path, 0777, 0777);

		// Set success message
		$message = array(
			'text' => 'Numbering model <strong>'.$num_model_file.'</strong> successfully generated.',
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
	'navbar_active' => 'model/num_model',
	'form_name' => 'model/num_model',
	'css' => array(),
	'js' => array('num_model.js'),
	'message' => $message,
	'modules_list' => getModulesList()
);

include_once '../views/layout.php';
