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
include_once __DIR__ . '/lib/zipper.php';

/**
 * Generate package
 */

$action = getPostData('action', '');
$message = array();
$links = array();
$packages_folder = 'packages';

if ($action == 'generate')
{
	// Get data
	$module_folder = getPostData('module_folder');
	$module_version = getModuleVersion($module_folder);
	if ($module_version == 'dolibase') {
		$module_version = getDolibaseVersion();
	}
	$package_name = 'module_'.$module_folder.'-'.$module_version.'.zip';

	// Check if ZipArchive class exist
	if (! class_exists('ZipArchive'))
	{
		// Set error message
		$message = array(
			'text' => 'Operations with archives are not available, <strong>ZipArchive</strong> class not found.',
			'type' => 'danger'
		);
	}
	// Check module version
	else if (empty($module_version))
	{
		// Set error message
		$message = array(
			'text' => 'Could not get <strong>'.$module_folder.'</strong> module version, this can happen when the module is not based on dolibase.',
			'type' => 'danger'
		);
	}
	// Check package filename length
	else if (strlen($package_name) > 32)
	{
		// Set error message
		$message = array(
			'text' => 'Package name <strong>'.$package_name.'</strong> length is greater than <strong>32</strong> & will not work on dolibarr, try changing the module folder name or version.',
			'type' => 'danger'
		);
	}
	else
	{
		// Get package path
		$root = getDolibarrRootDirectory();
		$package_path = $root.'/dolibase/builder/'.$packages_folder;
		$package_file = $package_path.'/'.$package_name;

		// Check if package file already exist
		if (file_exists($package_file))
		{
			// Set error message
			$message = array(
				'text' => 'Package <strong>'.$package_file.'</strong> already exists.',
				'type' => 'danger'
			);
			// Set delete link
			$links[] = array('text' => 'Delete', 'href' => $_SERVER['PHP_SELF'].'?action=delete&package_name='.$package_name, 'class' => 'btn btn-danger');
		}
		else
		{
			// Set current directory path to 'dolibarr/custom'
			chdir($root.'/custom');

			// Zip/Package module
			$zipper = new Zipper();
			$result = $zipper->create($package_file, $module_folder);

			if ($result && file_exists($package_file)) {
				// Set file permissions
				@chmod($package_file, 0777);
				// Set success message
				$message = array(
					'text' => 'Package <strong>'.$package_file.'</strong> successfully generated.',
					'type' => 'success'
				);
			}
			else {
				// Set error message
				$message = array(
					'text' => 'An error occurred while attempting to generate the module package.',
					'type' => 'danger'
				);
			}
		}

		// Set download link
		$root_url = pathinfo($_SERVER['PHP_SELF'], PATHINFO_DIRNAME);
		$links[] = array('text' => 'Download', 'href' => $root_url.'/'.$packages_folder.'/'.$package_name, 'class' => 'btn btn-success');
	}
}

/**
 * Delete package
 */

else if ($action == 'delete')
{
	// Get data
	$package_name = getPostData('package_name', 'get');
	$root = getDolibarrRootDirectory();
	$package_path = $root.'/dolibase/builder/'.$packages_folder;
	$package_file = $package_path.'/'.$package_name;

	// Check if package file exist
	if (file_exists($package_file))
	{
		// Delete package
		unlink($package_file);

		// Check if package file still exist
		if (file_exists($package_file))
		{
			// Set error message
			$message = array(
				'text' => 'Could not delete the package <strong>'.$package_file.'</strong>, check file permissions before retrying.',
				'type' => 'danger'
			);
		}
		else
		{
			// Set success message
			$message = array(
				'text' => 'Package <strong>'.$package_file.'</strong> successfully deleted.',
				'type' => 'success'
			);
		}
	}
	else
	{
		// Set error message
		$message = array(
			'text' => 'Package <strong>'.$package_file.'</strong> does not exist.',
			'type' => 'danger'
		);
	}
}

/**
 * Show view
 */

$options = array(
	'title' => 'Package Builder',
	'navbar_active' => 'package',
	'form_name' => 'package',
	'css' => array(),
	'js' => array(),
	'message' => $message,
	'modules_list' => getModulesList(),
	'links' => $links
);

include_once __DIR__ . '/views/layout.php';
