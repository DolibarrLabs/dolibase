<?php

global $dolibase_config;

/**
 * Module configuration
 */

$dolibase_config['module_name']     = 'YourModuleName';

$dolibase_config['module_desc']     = 'YourModuleDescription';

$dolibase_config['module_version']  = '1.0.0'; // Possible values: 'development', 'experimental', 'dolibarr' or version

$dolibase_config['module_number']   = '110000'; // e.: 110000 (avoid small numbers because they are used for core modules)

$dolibase_config['module_family']   = 'Dolibase modules'; // Possible values: 'crm', 'financial', 'hr', 'projects', 'products', 'ecm', 'technic', 'other'

$dolibase_config['module_position'] = 500;

$dolibase_config['module_url']      = '#'; // e.: 'https://www.dolistore.com/my_module'

$dolibase_config['module_folder']   = 'modulefoldername'; // Advice: never use underscores in module folder name to avoid many problems especially with top menu icon, module widgets etc..

$dolibase_config['module_picture']  = 'module_picture.png'; // Your picture file should have 'object_' prefix to work

$dolibase_config['module_depends']  = array(); // Modules class name(s) to activate when this one is activated, e.: array('modProduct');

$dolibase_config['module_dirs']     = array(); // Directories to create when activating the module, e.: array('/'.$dolibase_config['module_folder'].'/temp');

$dolibase_config['need_dolibarr']   = array(3, 8); // Minimum Dolibarr version, e.: array(6, 0);

$dolibase_config['need_php']        = array(5, 0); // Minimum PHP version, e.: array(4, 0);

$dolibase_config['required_by']     = array(); // Modules class name(s) to disable if this one is disabled, e.: array('modFacture');

$dolibase_config['conflict_with']   = array(); // Modules class name(s) who are in conflict with this module, e.: array('modProduct');

$dolibase_config['rights_class']    = 'my_module'; // key to reference module (for permissions, menus, etc.)

/**
 * Author informations
 */

$dolibase_config['editor_name']     = '<b>YourName</b>';

$dolibase_config['editor_url']      = '#';

$dolibase_config['editor_email']    = 'your@email.com';

$dolibase_config['dolistore_url']   = 'https://www.dolistore.com/';

/**
 * Others
 */

$dolibase_config['setup_page_url']  = 'setup.php'; // your setup page should be in admin folder

$dolibase_config['about_page_url']  = 'about.php'; // should also be in admin folder

$dolibase_config['lang_files']      = array($dolibase_config['module_folder'].'@'.$dolibase_config['module_folder']); // Leave it like that if you always use the same name for your module folder & your language files (this array should never be empty)

$dolibase_config['menu_lang_file']  = $dolibase_config['lang_files'][0];

$dolibase_config['top_menu_name']   = str_replace('_', '', $dolibase_config['module_folder']);

/**
 * Numbering models (optional)
 */

$dolibase_config['num_model_table']  = 'my_table'; // Table name without prefix

$dolibase_config['num_model_field']  = 'ref';

$dolibase_config['num_model_prefix'] = 'PR';

/**
 * Load Dolibase
 */

// Define __DIR__ for PHP version < 5.3
if (! defined('__DIR__')) define('__DIR__', dirname(__FILE__));

// Load Dolibase only if not already loaded by another module
if (! defined('DOLIBASE_VERSION') && false === (@include_once __DIR__ . '/../dolibase/autoload.php')) { // From htdocs directory
	if (false === (@include_once __DIR__ . '/../../dolibase/autoload.php')) { // From "custom" directory
		require_once __DIR__ . '/dolibase/autoload.php'; // From module directory
	}
}
