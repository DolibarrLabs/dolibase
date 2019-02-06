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

include_once __DIR__ . '/lib/functions.php';
include_once __DIR__ . '/lib/simple_image.php';

/**
 * Generate widget
 */

$action = getPostData('action');
$message = array();

if ($action == 'generate')
{
	// Get data
	$widget_name = getPostData('widget_name');
	$use_custom_class = getPostData('use_custom_class');
	$data = array(
		'module_folder' => getPostData('module_folder'),
		'widget_name' => $widget_name,
		'widget_position' => getPostData('widget_position'),
		'widget_picture' => sanitizeString(strtolower($widget_name)).'.'.getFileExtension($_FILES['widget_picture']['name']),
		'widget_title' => getPostData('widget_title'),
		'enable_widget' => bool2Int(getPostData('enable_widget'))
	);

	// Check if widget already exist
	$root = getDolibarrRootDirectory();
	$module_path = $root.'/custom/'.$data['module_folder'];
	$widget_path = $module_path.'/core/boxes';
	$widget_filename = sanitizeString(strtolower($widget_name)).'.php';
	$widget_file = $widget_path.'/'.$widget_filename;

	if (file_exists($widget_file))
	{
		// Set error message
		$message = array(
			'text' => 'Widget <strong>'.$widget_file.'</strong> already exists.',
			'type' => 'danger'
		);
	}
	else
	{
		// Create widget folder
		mkdir_r(array($widget_path), 0777);

		// Upload widget picture
		$picture_target_dir = $module_path.'/img/';
		$picture_target_file = $picture_target_dir.'object_'.$data['widget_picture'];
		move_uploaded_file($_FILES['widget_picture']['tmp_name'], $picture_target_file);

		// Resize widget picture
		$image = new SimpleImage();
		$image->load($picture_target_file);
		$image->resize(16, 16);
		$image->save($picture_target_file, $image->getImageType());

		// Create widget class
		$data['widget_class_name'] = sanitizeString(ucfirst($widget_name));
		$data['dolibase_class_name'] = 'Widget';
		$data['dolibase_class_include']  = "dolibase_include_once('core/class/widget.php');";
		if ($use_custom_class) {
			// Copy widget class into module folder & rename it from Widget to WidgetXXX where XXX represent the current version of dolibase
			$dolibase_version = getDolibaseVersion($root);
			if (! empty($dolibase_version)) {
				if (copy($root.'/dolibase/core/class/widget.php', $module_path.'/class/widget.php')) {
					$version_numbers = explode('.', $dolibase_version);
					foreach ($version_numbers as $num) {
						$data['dolibase_class_name'] .= num2Alpha((int) $num);
					}
					file_replace_contents($module_path.'/class/widget.php', '({\n+)\/\*.*?\*\/\n+(class)', '$1$2', '/', '/s'); // remove class comment
					file_replace_contents($module_path.'/class/widget.php', 'Widget', $data['dolibase_class_name']);
					$data['dolibase_class_include'] = "dol_include_once('".$data['module_folder']."/class/widget.php');";
				}
			}
		}
		$template = getTemplate(__DIR__ . '/tpl/widget/class.php', $data);
		file_put_contents($widget_file, $template);

		// Add widget in module class
		$module_file = getModuleFileName($module_path);
		file_replace_contents($module_file, 'public function loadSettings\(\)(.*?)\{', "public function loadSettings()\n\t{\n\t\t".'// '.$widget_name."\n\t\t".'$this->addWidget("'.$widget_filename.'");'."\n", '/', '/s');

		// Set files/folders permissions
		chmod_r($module_path, 0777, 0777);

		// Set success message
		$message = array(
			'text' => 'Widget <strong>'.$widget_file.'</strong> successfully generated.',
			'type' => 'success'
		);
	}
}

/**
 * Show view
 */

$options = array(
	'title' => 'Widget Builder',
	'navbar_active' => 'widget',
	'form_name' => 'widget',
	'css' => array(),
	'js' => array(),
	'message' => $message,
	'modules_list' => getModulesList()
);

include_once __DIR__ . '/views/layout.php';
