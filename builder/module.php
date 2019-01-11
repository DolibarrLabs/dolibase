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
	$add_top_menu = getPostData('add_top_menu');
	$add_generic_left_menu = getPostData('add_generic_left_menu');
	$add_crud_perms = getPostData('add_crud_perms');
	$add_extrafields_page = getPostData('add_extrafields_page');
	$add_changelog_page = getPostData('add_changelog_page');
	$add_num_models_settings = getPostData('add_num_models_settings');
	$add_doc_models_settings = getPostData('add_doc_models_settings');
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
		'enable_triggers' => bool2Alpha(getPostData('enable_triggers')),
		'enable_for_external_users' => bool2Alpha(getPostData('enable_for_external_users')),
		'author_name' => getPostData('author_name'),
		'author_url' => getPostData('author_url'),
		'author_email' => getPostData('author_email'),
		'author_dolistore_url' => getPostData('author_dolistore_url'),
		'num_model_table' => getPostData('num_model_table'),
		'num_model_field' => getPostData('num_model_field'),
		'num_model_prefix' => getPostData('num_model_prefix')
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
		'sql',
		'tpl'
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

		// Create module autoload file
		$template = getTemplate(__DIR__ . '/tpl/module/autoload.php');
		file_put_contents($module_path.'/autoload.php', $template);

		// Create setup page
		$setup_data = array(
			'add_extrafields_tab' => bool2Alpha($add_extrafields_page),
			'add_changelog_tab' => bool2Alpha($add_changelog_page),
			'settings' => ($add_num_models_settings || $add_doc_models_settings ? '' : '$page->setupNotAvailable();')
		);
		if ($add_num_models_settings) {
			$setup_data['settings'] .= '$page->addSubtitle("NumberingModels");'."\n\n".'$page->printNumModels();';
		}
		if ($add_doc_models_settings) {
			$setup_data['settings'] .= ($add_num_models_settings ? "\n\n" : '').'$page->addSubtitle("DocumentModels");'."\n\n".'$page->printDocModels();';
			// Add doc model class into module (to fix documents block on card page)
			$doc_model_dir = $module_path.'/core/modules/'.$data['folder'];
			$doc_model_data = array(
				'module_folder' => $data['folder'],
				'model_class' => ucfirst($module_name)
			);
			mkdir_r(array($doc_model_dir), 0777);
			$doc_model_template = getTemplate(__DIR__ . '/tpl/module/doc_model_class.php', $doc_model_data);
			file_put_contents($doc_model_dir.'/modules_'.$data['folder'].'.php', $doc_model_template);
		}
		$setup_template = getTemplate(__DIR__ . '/tpl/module/setup.php', $setup_data);
		file_put_contents($module_path.'/admin/setup.php', $setup_template);

		// Create about page
		$about_data = array(
			'add_extrafields_tab' => $setup_data['add_extrafields_tab'],
			'add_changelog_tab' => $setup_data['add_changelog_tab'],
			'picture' => $data['picture']
		);
		$about_template = getTemplate(__DIR__ . '/tpl/module/about.php', $about_data);
		file_put_contents($module_path.'/admin/about.php', $about_template);

		// Create extrafields page
		if ($add_extrafields_page) {
			$element_type = sanitizeString(strtolower($module_name));
			$extrafields_data = array(
				'element_type' => $element_type,
				'add_changelog_tab' => $setup_data['add_changelog_tab']
			);
			$extrafields_template = getTemplate(__DIR__ . '/tpl/module/extrafields.php', $extrafields_data);
			file_put_contents($module_path.'/admin/extrafields.php', $extrafields_template);
			// Create extrafields table
			$extrafields_table_data = array(
				'current_year' => date('Y'),
				'author_name' => $data['author_name'],
				'table_name' => $element_type.'_extrafields' // without prefix (llx_)
			);
			$extrafields_table_template = getTemplate(__DIR__ . '/tpl/module/extrafields.sql', $extrafields_table_data);
			file_put_contents($module_path.'/sql/llx_'.$extrafields_table_data['table_name'].'.sql', $extrafields_table_template);
			$extrafields_key_sql_template = getTemplate(__DIR__ . '/tpl/module/extrafields.key.sql', $extrafields_table_data);
			file_put_contents($module_path.'/sql/llx_'.$extrafields_table_data['table_name'].'.key.sql', $extrafields_key_sql_template);
		}

		// Create changelog page
		if ($add_changelog_page) {
			$changelog_data = array(
				'add_extrafields_tab' => $setup_data['add_extrafields_tab']
			);
			$changelog_template = getTemplate(__DIR__ . '/tpl/module/changelog.php', $changelog_data);
			file_put_contents($module_path.'/admin/changelog.php', $changelog_template);
			// Create changelog.json file
			$changelog_json_data = array(
				'version' => $data['version'],
				'current_date' => date('Y/m/d')
			);
			$changelog_json_template = getTemplate(__DIR__ . '/tpl/module/changelog.json', $changelog_json_data);
			file_put_contents($module_path.'/changelog.json', $changelog_json_template);
		}

		// Create module class
		$module_class_data = array(
			'module_folder' => $data['folder'],
			'module_class_name' => sanitizeString(ucfirst($module_name)),
			'dolibase_class_name' => 'DolibaseModule',
			'dolibase_class_include' => "dolibase_include_once('core/class/module.php');",
			'module_settings' => ''
		);
		$perms_translation = '';
		if ($add_top_menu) {
			// Add top menu
			$module_class_data['module_settings'] .= '// Top Menu';
			$module_class_data['module_settings'] .= "\n\t\t".'$this->addTopMenu($this->config["other"]["top_menu_name"], "'.$module_name.'", "/'.$data['folder'].'/index.php");';
			// Add menu icon (this is mandatory starting from Dolibarr 8)
			copy($picture_target_file, $picture_target_dir.InsertBeforeFileExtension($data['picture'], '_over'));
		}
		if ($add_generic_left_menu) {
			// Add generic left menu
			$leftmenu_name = strtolower($module_name);
			$module_class_data['module_settings'] .= "\n\n\t\t".'// Left Menu';
			// Index page
			$module_class_data['module_settings'] .= "\n\t\t".'$this->addLeftMenu($this->config["other"]["top_menu_name"], "'.$leftmenu_name.'", "'.$module_name.'", "/'.$data['folder'].'/index.php");';
			// Create page
			$module_class_data['module_settings'] .= "\n\t\t".'$this->addLeftSubMenu($this->config["other"]["top_menu_name"], "'.$leftmenu_name.'", "", "New", "/'.$data['folder'].'/new.php");';
			// List page
			$module_class_data['module_settings'] .= "\n\t\t".'$this->addLeftSubMenu($this->config["other"]["top_menu_name"], "'.$leftmenu_name.'", "", "List", "/'.$data['folder'].'/list.php");';
		}
		if ($add_crud_perms) {
			// Add CRUD permissions
			$crud_perms = array(
				array('name' => 'read', 'desc' => 'Read permission', 'type' => 'r'),
				array('name' => 'create', 'desc' => 'Create permission', 'type' => 'c'),
				array('name' => 'modify', 'desc' => 'Modify permission', 'type' => 'm'),
				array('name' => 'delete', 'desc' => 'Delete permission', 'type' => 'd')
			);

			$module_class_data['module_settings'] .= "\n\n\t\t".'// Permissions';
			$perm_number = (int) $data['number'];

			foreach ($crud_perms as $perm) {
				$module_class_data['module_settings'] .= "\n\t\t".'$this->addPermission("'.$perm['name'].'", "'.$perm['desc'].'", "'.$perm['type'].'");';
				$perms_translation .= 'Permission'.(++$perm_number).' = '.$perm['desc']."\n";
			}
		}
		if ($use_custom_class) {
			// Copy dolibase module class into module folder & rename it from DolibaseModule to DolibaseModuleXXX where XXX represent the current version of dolibase
			$dolibase_version = getDolibaseVersion($root);
			if (! empty($dolibase_version)) {
				if (copy($root.'/dolibase/core/class/module.php', $module_path.'/class/module.php')) {
					$version_numbers = explode('.', $dolibase_version);
					foreach ($version_numbers as $num) {
						$module_class_data['dolibase_class_name'] .= num2Alpha((int) $num);
					}
					file_replace_contents($module_path.'/class/module.php', '({\n+)\/\*.*?\*\/\n+(class)', '$1$2', '/', '/s'); // remove class comment
					file_replace_contents($module_path.'/class/module.php', 'DolibaseModule', $module_class_data['dolibase_class_name']);
					$module_class_data['dolibase_class_include'] = "dol_include_once('".$data['folder']."/class/module.php');";
				}
			}
		}
		$module_class_template = getTemplate(__DIR__ . '/tpl/module/class.php', $module_class_data);
		file_put_contents($module_path.'/core/modules/mod'.$module_class_data['module_class_name'].'.class.php', $module_class_template);

		// Create langs files
		$lang_data = array(
			'module_name' => strtoupper($module_name),
			'current_year' => date('Y'),
			'author_name' => $data['author_name'],
			'module_name_translation' => 'Module'.$data['number'].'Name = '.$module_name,
			'module_desc_translation' => 'Module'.$data['number'].'Desc = '.$module_name,
			'permissions_translation' => $perms_translation
		);
		$english_template = getTemplate(__DIR__ . '/tpl/module/en_US.lang', $lang_data);
		file_put_contents($module_path.'/langs/en_US/'.$data['folder'].'.lang', $english_template);
		$french_template = getTemplate(__DIR__ . '/tpl/module/fr_FR.lang', $lang_data);
		file_put_contents($module_path.'/langs/fr_FR/'.$data['folder'].'.lang', $french_template);

		// Add a copy of dolibase
		$dolibase_filter = array(
			'builder',
			'docs',
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
	'title' => 'Module Builder',
	'navbar_active' => 'module',
	'form_name' => 'module',
	'css' => array(),
	'js' => array('module.js'),
	'message' => $message,
	'author_info' => getAuthorInfo()
);

include_once __DIR__ . '/views/layout.php';
