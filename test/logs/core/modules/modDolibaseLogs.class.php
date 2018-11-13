<?php

// Load Dolibase
dol_include_once('/logs/autoload.php');
// Load Dolibase Module class
dolibase_include_once('/core/class/module.php');

/**
 *	Class to describe and enable module
 */
class modDolibaseLogs extends DolibaseModule
{
	/**
	 * Function called after module configuration.
	 * 
	 */
	public function loadSettings()
	{
		// Set permissions
		$this->addPermission("read", "Read logs", "r");

		// Add menus
		$menu_title = compare_version(DOL_VERSION, '<' ,'7.0.0') ? "DolibaseLogs" : "DolibaseLogsWithIcon";
		$this->addLeftMenu($this->config['other']['top_menu_name'], "logs", $menu_title, "/logs/index.php", '$user->rights->dolibase_logs->read');
	}
}
