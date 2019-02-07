<?php

global $dolibase_config;

/**
 * Module configuration
 */

$dolibase_config['module'] = array(
	'name'          => 'Books',
	'desc'          => 'Books Managment Module',
	'version'       => 'dolibase', // possible values: 'development', 'experimental', 'dolibarr' or version
	'number'        => '450000', // e.: 110000 (avoid small numbers because they are used for core modules)
	'family'        => 'Dolibase modules', // possible values: 'crm', 'financial', 'hr', 'projects', 'products', 'ecm', 'technic', 'other'
	'position'      => 500,
	'rights_class'  => 'books', // key to reference module (for permissions, menus, etc.)
	'url'           => '#', // e.: 'https://www.dolistore.com/my_module'
	'folder'        => 'books', // advice: never use underscores in module folder name to avoid many problems especially with top menu icon, module widgets etc..
	'picture'       => 'books.png', // your picture file should have 'object_' prefix to work
	'dirs'          => array(), // directories to create when activating the module, e.: array('/modulefoldername/temp')
	'dolibarr_min'  => array(3, 8), // minimum Dolibarr version, e.: array(6, 0)
	'php_min'       => array(5, 3), // minimum PHP version, e.: array(4, 0)
	'depends'       => array(), // modules to activate when this one is activated, e.: array('modProduct')
	'required_by'   => array(), // modules to disable if this one is disabled, e.: array('modFacture')
	'conflit_with'  => array(), // modules who are in conflict with this module, e.: array('modProduct')
	'check_updates' => true, // check for module updates (module url should be filled also)
	'enable_logs'   => true // enable saving logs when create/modify or delete an object
);

/**
 * Author informations
 */

$dolibase_config['author'] = array(
	'name'          => '<b>AXeL</b>',
	'url'           => 'https://github.com/AXeL-dev',
	'email'         => 'contact.axel.dev@gmail.com',
	'dolistore_url' => 'https://www.dolistore.com/en/search?orderby=position&orderway=desc&search_query=axel'
);

/**
 * Numbering model (optional)
 */

$dolibase_config['numbering_model'] = array(
	'table'  => 'books', // table name without prefix
	'field'  => 'ref',
	'prefix' => 'BK'
);

/**
 * Other (default)
 */

$dolibase_config['other'] = array(
	'setup_page'     => 'setup.php', // your setup page should be in admin folder
	'about_page'     => 'about.php', // should also be in admin folder
	'lang_files'     => array($dolibase_config['module']['folder'].'@'.$dolibase_config['module']['folder']), // leave it like that if you always use the same name for your module folder & your language files (this array should never be empty)
	'menu_lang_file' => $dolibase_config['module']['folder'].'@'.$dolibase_config['module']['folder'], // e.: 'mylangfile@modulefolder'
	'top_menu_name'  => str_replace('_', '', $dolibase_config['module']['folder']) // e.: 'mytopmenu' (no underscores)
);
