<?php

return array(
	/**
	 * Module configuration
	 */
	'module' => array(
		'name'                      => 'YourModuleName',
		'desc'                      => 'YourModuleDescription',
		'version'                   => '1.0.0', // possible values: 'development', 'experimental', 'dolibarr' or version
		'number'                    => '110000', // e.: 110000 (avoid small numbers because they are used for core modules)
		'family'                    => 'Dolibase modules', // possible values: 'hr', 'crm', 'srm', 'financial', 'products', 'projects', 'ecm', 'technic', 'portal', 'interface', 'base', 'other'
		'position'                  => 500,
		'rights_class'              => 'my_module', // key to reference module (for permissions, menus, etc.)
		'url'                       => '#', // e.: 'https://www.dolistore.com/my_module'
		'folder'                    => 'modulefoldername', // advice: never use underscores in module folder name to avoid many problems especially with top menu icon, module widgets etc..
		'picture'                   => 'module_picture.png', // your picture file should have 'object_' prefix to work
		'dirs'                      => array(), // directories to create when activating the module, e.: array('/modulefoldername/temp')
		'dolibarr_min'              => array(3, 8), // minimum Dolibarr version, e.: array(6, 0)
		'php_min'                   => array(5, 3), // minimum PHP version, e.: array(4, 0)
		'depends'                   => array(), // modules to activate when this one is activated, e.: array('modProduct')
		'required_by'               => array(), // modules to disable if this one is disabled, e.: array('modFacture')
		'conflit_with'              => array(), // modules who are in conflict with this module, e.: array('modProduct')
		'check_updates'             => true, // check for module updates (module url should be filled also)
		'enable_logs'               => true, // enable saving logs when create/modify or delete an object
		'enable_triggers'           => false, // enable module triggers
		'enable_for_external_users' => false // enable module for external users
	),
	/**
	 * Author informations
	 */
	'author' => array(
		'name'          => '<b>YourName</b>',
		'url'           => '#',
		'email'         => 'your@email.com',
		'dolistore_url' => 'https://www.dolistore.com/'
	),
	/**
	 * Numbering model (optional)
	 */
	'numbering_model' => array(
		'table'  => 'my_table', // table name without prefix
		'field'  => 'ref',
		'prefix' => 'PR'
	),
	/**
	 * Other (default)
	 */
	'other' => array(
		'setup_page'     => 'setup.php', // your setup page should be in admin folder
		'about_page'     => 'about.php', // should also be in admin folder
		'lang_files'     => array('langfilename@modulefoldername'), // this array should never be empty
		'menu_lang_file' => 'menulangfile@modulefoldername', // e.: 'mylangfile@modulefolder'
		'top_menu_name'  => 'mytopmenu' // e.: 'mytopmenu' (no underscores)
	)
);
