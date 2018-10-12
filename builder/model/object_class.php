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
 * Generate object class
 */

$action = getPostData('action');
$message = array();

if ($action == 'generate')
{
	// Get data
	$object_classname = sanitizeString(getPostData('object_classname'));
	$date_fields = getPostData('date_fields');
	$data = array(
		'module_folder' => getPostData('module_folder'),
		'object_classname' => ucfirst($object_classname), 
		'object_element' => getPostData('object_element'),
		'object_table' => getPostData('object_table'),
		'fetch_fields' => getPostData('fetch_fields'),
		'fetch_function_content' => '',
		'fetchAll_function_content' => '',
		'tooltip_title' => getPostData('tooltip_title')
	);

	// Check if object class already exist
	$root = getDolibarrRootDirectory();
	$module_path = $root.'/custom/'.$data['module_folder'];
	$object_class_path = $module_path.'/class';
	$object_class_filename = strtolower($object_classname).'.class.php';
	$object_class_file = $object_class_path.'/'.$object_class_filename;

	if (file_exists($object_class_file))
	{
		// Set error message
		$message = array(
			'text' => 'Object class <strong>'.$object_class_file.'</strong> already exists.',
			'type' => 'danger'
		);
	}
	else
	{
		// Sanitize fetch fields
		$fetch_fields = explode(',', $data['fetch_fields']);
		$sanitized_fetch_fields = '';
		foreach ($fetch_fields as $field) {
			$trimed_field = trim($field);
			if (! empty($trimed_field)) {
				$sanitized_fetch_fields .= "'".str_replace(array('\'', '"'), '', $trimed_field)."', ";
			}
		}
		$data['fetch_fields'] = substr($sanitized_fetch_fields, 0, -2); // remove the last ', '

		// Generate content for fetch & fetchAll functions
		if (is_array($date_fields) && ! empty($date_fields)) {
			$data['fetch_function_content'] = $data['fetchAll_function_content'] = '// Fix error: dol_print_date function call with deprecated value of time';
			$data['fetchAll_function_content'] .= "\n\t\t".'for ($i = 0; $i < count($this->lines); $i++) {';
			foreach ($date_fields as $field) {
				$data['fetch_function_content'] .= "\n\t\t".'$this->'.$field.' = $this->db->jdate($this->'.$field.');';
				$data['fetchAll_function_content'] .= "\n\t\t\t".'$this->lines[$i]->'.$field.' = $this->db->jdate($this->lines[$i]->'.$field.');';
			}
			$data['fetchAll_function_content'] .= "\n\t\t}";
		}

		// Add object class into module
		$template = getTemplate(__DIR__ . '/../tpl/model/object_class.php', $data);
		file_put_contents($object_class_file, $template);

		// Set file permission
		chmod($object_class_file, 0777);

		// Set success message
		$message = array(
			'text' => 'Object class <strong>'.$object_class_file.'</strong> successfully generated.',
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
	'navbar_active' => 'model/object_class',
	'form_name' => 'model/object_class',
	'css' => array(),
	'js' => array('object_class.js'),
	'message' => $message,
	'modules_list' => getModulesList()
);

include_once '../views/layout.php';
