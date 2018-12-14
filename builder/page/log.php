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
	$module_folder = getPostData('module_folder');
	$object_class = getPostData('object_class');
	$add_card_tab = getPostData('add_card_tab');
	$add_document_tab = getPostData('add_document_tab');
	$data = array(
		'module_folder' => $module_folder,
		'page_name' => $page_name,
		'page_title' => getPostData('page_title'),
		'access_perms' => getPostData('access_perms'),
		'object_class_include' => "dolibase_include_once('/core/class/custom_object.php');",
		'object_init' => '$object = new CustomObject();'."\n".'// $object->setTableName(...);',
		'tabs' => ''
	);

	// Check if page already exist
	$root = getDolibarrRootDirectory();
	$module_path = $root.'/custom/'.$module_folder;
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
		// Set object class include & init
		if (! empty($object_class)) {
			$data['object_class_include'] = "dol_include_once('/".$module_folder."/class/".$object_class."');";
			$data['object_init'] = '$object = new '.getClassName($module_path.'/class/'.$object_class).'();';
		}

		// Add card page tab
		if ($add_card_tab) {
			$data['tabs'] .= '$page->addTab("Card", "'.$module_folder.'/card.php?id=".$id."&ref=".$ref);';
		}

		// Add document page tab
		if ($add_document_tab) {
			$data['object_class_include'] .= "\n// Load Document Page class\ndolibase_include_once('/core/pages/document.php');";
			$data['tabs'] .= (empty($data['tabs']) ? '' : "\n\t").'$page->addTab(DocumentPage::getTabTitle($object), "'.$module_folder.'/document.php?id=".$id."&ref=".$ref);';
		}

		// Add log page tab
		$data['tabs'] .= (empty($data['tabs']) ? '' : "\n\t").'$page->addTab("Log", "'.$module_folder.'/'.$page_name.'?id=".$id."&ref=".$ref, true);';

		// Add page into module
		$template = getTemplate(__DIR__ . '/../tpl/page/log.php', $data);
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

$modules_list = getModulesList();
$object_class_list = empty($modules_list) ? array() : getModuleObjectClassList($modules_list[0]);
$rights_class = empty($modules_list) ? '' : getModuleRightsClass($modules_list[0]);
$options = array(
	'path_prefix' => '../',
	'title' => 'Page Builder',
	'navbar_active' => 'page/log',
	'form_name' => 'page/log',
	'css' => array(),
	'js' => array('page.js'),
	'message' => $message,
	'modules_list' => $modules_list,
	'object_class_list' => $object_class_list,
	'access_perms' => '$user->rights->'.$rights_class.'->read'
);

include_once '../views/layout.php';
