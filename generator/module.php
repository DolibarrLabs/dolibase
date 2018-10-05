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
 * Generate module
 */

$action = getPostData('action');
$message = array();

if ($action == 'generate')
{
	// Get data
	$module_name = getPostData('name');
	$use_custom_class = getPostData('use_custom_class');
	$data = array(
		'name' => $module_name,
		'description' => getPostData('description'),
		'version' => getPostData('version'),
		'number' => getPostData('number'),
		'family' => getPostData('family'),
		'position' => getPostData('position'),
		'rights_class' => sanitizeString(getPostData('rights_class')),
		'url' => getPostData('url'),
		'folder' => sanitizeString(getPostData('folder'), true),
		'picture' => sanitizeString(strtolower($module_name)).'.'.getFileExtension($_FILES['picture']['name']),
		'check_updates' => bool2Alpha(getPostData('check_updates')),
		'enable_logs' => bool2Alpha(getPostData('enable_logs')),
		'author_name' => getPostData('author_name'),
		'author_url' => getPostData('author_url'),
		'author_email' => getPostData('author_email'),
		'author_dolistore_url' => getPostData('author_dolistore_url')
	);

	// Create module folder & sub-folders
	$root = getDolibarrRootDirectory();
	$module_path = $root.'/custom/'.$data['folder'];
	$module_sub_folders = array(
		'admin',
		'class',
		'core/modules',
		'css',
		'js',
		'img',
		'langs/en_US',
		'langs/fr_FR',
		'sql'
	);

	if (! mkdir_r($module_sub_folders, 0777, $module_path))
	{
		// Set error message
		$message = array(
			'text' => 'Module folder <strong>'.$module_path.'</strong> already exists.',
			'type' => 'danger'
		);
	}
	else
	{
		// Upload/Set module picture
		$picture_target_dir = $module_path.'/img/';
		$picture_target_file = $picture_target_dir.$data['picture'];
		move_uploaded_file($_FILES['picture']['tmp_name'], $picture_target_file);

		// Add mini picture
		$image = new SimpleImage();
		$image->load($picture_target_file);
		$image->resize(16, 16);
		$image->save($picture_target_dir.'object_'.$data['picture'], $image->getImageType());

		// Create module config file
		$template = getTemplate(__DIR__ . '/tpl/module/config.php', $data);
		file_put_contents($module_path.'/config.php', $template);

		// Create setup & about pages
		$setup_template = getTemplate(__DIR__ . '/tpl/module/setup.php');
		file_put_contents($module_path.'/admin/setup.php', $setup_template);
		$about_template = getTemplate(__DIR__ . '/tpl/module/about.php', array('picture' => $data['picture']));
		file_put_contents($module_path.'/admin/about.php', $about_template);

		// Create module class
		$module_class_data = array(
			'module_folder' => $data['folder'],
			'module_class_name' => sanitizeString(ucfirst($module_name)),
			'dolibase_class_name' => 'DolibaseModule',
			'dolibase_class_include' => "dolibase_include_once('/core/class/module.php');"
		);
		if ($use_custom_class) {
			// Copy dolibase module class into module folder & rename it from DolibaseModule to DolibaseModuleXXX where XXX represent the current version of dolibase
			$dolibase_version = getDolibaseVersion($root);
			if (! empty($dolibase_version)) {
				if (copy($root.'/dolibase/core/class/module.php', $module_path.'/class/module.php')) {
					$version_numbers = explode('.', $dolibase_version);
					foreach ($version_numbers as $num) {
						$module_class_data['dolibase_class_name'] .= num2Alpha((int) $num);
					}
					file_replace_contents($module_path.'/class/module.php', 'DolibaseModule', $module_class_data['dolibase_class_name']);
					$module_class_data['dolibase_class_include'] = "dol_include_once('/".$data['folder']."/class/module.php');";
				}
			}
		}
		$module_class_template = getTemplate(__DIR__ . '/tpl/module/class.php', $module_class_data);
		file_put_contents($module_path.'/core/modules/mod'.$module_class_data['module_class_name'].'.class.php', $module_class_template);

		// Create langs files
		$lang_data = array(
			'module_name' => strtoupper($module_name),
			'current_year' => date('Y'),
			'author_name' => $data['author_name']
		);
		$english_template = getTemplate(__DIR__ . '/tpl/module/en_US.lang', $lang_data);
		file_put_contents($module_path.'/langs/en_US/'.$data['folder'].'.lang', $english_template);
		$french_template = getTemplate(__DIR__ . '/tpl/module/fr_FR.lang', $lang_data);
		file_put_contents($module_path.'/langs/fr_FR/'.$data['folder'].'.lang', $french_template);

		// Add a copy of dolibase
		$dolibase_filter = array(
			'generator',
			'test',
			'LICENSE',
			'changelog.md',
			'todo.md',
			'README.md',
			'.git',
			'.gitignore'
		);
		recurse_copy($root.'/dolibase', $module_path.'/dolibase', $dolibase_filter);

		// Set files/folders permissions
		chmod_r($module_path, 0777, 0777);

		// Set success message
		$message = array(
			'text' => 'Module <strong>'.$module_name.'</strong> successfully generated into <strong>'.$module_path.'</strong> directory.',
			'type' => 'success'
		);
	}
}

/**
 * Show view
 */

$options = array(
	'title' => 'Module Generator',
	'navbar_active' => 'module',
	'form_name' => 'module',
	'css' => array(),
	'js' => array('module.js'),
	'message' => $message,
	'author_info' => getAuthorInfo()
);

include_once __DIR__ . '/views/layout.php';
