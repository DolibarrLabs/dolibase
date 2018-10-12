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

/**
 * Generate translation
 */

$action = getPostData('action');
$message = array();

if ($action == 'generate')
{
	// Get data
	$data = array(
		'module_folder' => getPostData('module_folder'),
		'lang_folder' => getPostData('lang_folder'),
		'lang_file' => getPostData('lang_file'),
		'author_name' => getPostData('author_name'),
		'translation_folder_name' => getPostData('translation_folder_name')
	);

	// Check if translation already exist
	$root = getDolibarrRootDirectory();
	$module_path = $root.'/custom/'.$data['module_folder'];
	$translation_path = $module_path.'/langs/'.$data['translation_folder_name'];
	$translation_file = $translation_path.'/'.$data['lang_file'];

	if (file_exists($translation_file))
	{
		// Set error message
		$message = array(
			'text' => 'Translation <strong>'.$translation_file.'</strong> already exists.',
			'type' => 'danger'
		);

		// Set view to default
		$action = '';
	}
	else
	{
		// Get translation strings
		$translation_origin_file = $module_path.'/langs/'.$data['lang_folder'].'/'.$data['lang_file'];
		$translation_strings = array();

		foreach(file($translation_origin_file) as $line) {
			if (preg_match('/(^[^#].*?)=(.*)/', $line, $matches)) { // get only lines that don't start with '#' & contains an '=' character
				$translation_strings[trim($matches[1])] = trim($matches[2]);
			}
		}

		// Set info message
		$message = array(
			'text' => 'Update translation strings & hit <a href="#save">save</a> button when you\'re done.',
			'type' => 'info'
		);
	}
}

/**
 * Save translation
 */

else if ($action == 'save')
{
	// Get data
	$module_folder = getPostData('module_folder');
	//$lang_folder = getPostData('lang_folder');
	$lang_file = getPostData('lang_file');
	$translation_folder_name = getPostData('translation_folder_name');
	$translation_keys = getPostData('translation_keys');
	$translation_values = getPostData('translation_values');
	$data = array(
		'module_name' => strtoupper($module_folder), // TODO: get module name from config file
		'translation_name' => $translation_folder_name,
		'current_year' => date('Y'),
		'author_name' => getPostData('author_name'),
		'translation_strings' => ''
	);

	// Check if translation already exist
	$root = getDolibarrRootDirectory();
	$module_path = $root.'/custom/'.$module_folder;
	$translation_path = $module_path.'/langs/'.$translation_folder_name;
	$translation_file = $translation_path.'/'.$lang_file;

	if (file_exists($translation_file))
	{
		// Set error message
		$message = array(
			'text' => 'Translation <strong>'.$translation_file.'</strong> already exists.',
			'type' => 'danger'
		);
	}
	else
	{
		// Create lang folder if not exist
		mkdir_r(array($translation_path), 0777);

		// Generate translation strings
		foreach ($translation_values as $key => $value) {
			$data['translation_strings'] .= $translation_keys[$key].' = '.$value."\n";
		}

		// Create lang file
		$template = getTemplate(__DIR__ . '/tpl/translation/file.lang', $data);
		file_put_contents($translation_file, $template);

		// Set files/folders permissions
		chmod_r($module_path, 0777, 0777);

		// Set success message
		$message = array(
			'text' => 'Translation <strong>'.$translation_file.'</strong> successfully generated.',
			'type' => 'success'
		);
	}
}

/**
 * Show view
 */

if ($action == 'generate')
{
	$options = array(
		'title' => 'Translation Builder',
		'navbar_active' => 'translation',
		'form_name' => 'translation/save',
		'css' => array(),
		'js' => array(),
		'message' => $message,
		'data' => $data,
		'translation_strings' => $translation_strings
	);
}
else
{
	$modules_list = getModulesList();
	$lang_folder_list = empty($modules_list) ? array() : getModuleLangList($modules_list[0]);
	$lang_file_list = empty($lang_folder_list) ? array() : getModuleLangFileList($modules_list[0], $lang_folder_list[0]);
	$options = array(
		'title' => 'Translation Builder',
		'navbar_active' => 'translation',
		'form_name' => 'translation/generate',
		'css' => array(),
		'js' => array('translation.js'),
		'message' => $message,
		'modules_list' => $modules_list,
		'lang_folder_list' => $lang_folder_list,
		'lang_file_list' => $lang_file_list,
		'author_info' => getAuthorInfo()
	);
}

include_once __DIR__ . '/views/layout.php';
