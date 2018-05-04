<?php

// Load Dolibase config file for this module (mandatory)
include_once dirname(__FILE__) . '/../../config.php'; // this file is called from Dolibarr admin folder so we use dirname(__FILE__)
// Load Dolibase Module class
dolibase_include_once('/core/class/module.php');

/**
 *	Class to describe and enable module
 */
class modMyFirstModule extends DolibaseModule
{
	/**
	 * Function called after module configuration.
	 * 
	 */
	public function loadSettings()
	{
		$this->addConstant("MY_FIRST_MODULE_CONST", "test");
		$this->addConstant("MY_FIRST_MODULE_SECOND_CONST", "test2");

		$this->addWidget("mybox.php");

		$this->addCssFile("mycss.css.php");
		//$this->addCssFile("mycss2.css.php");

		$this->addPermission("use", "UseTheModule");
		//$this->addPermission("read", "Read permission", "r");
		//$this->addPermission("create", "Create permission", "c");
		//$this->addPermission("modify", "Modify permission", "m");
		//$this->addPermission("delete", "Delete permission", "d");

		//$this->addSubPermission("delete", "all", "Delete all permissions", "d");

		$this->addTopMenu($this->config['top_menu_name'], "MyFirstMenu", "/myfirstmodule/index.php?test=1");

		//$this->addTopMenu("mysecondmenu", "MySecondMenu", "/myfirstmodule/index.php?test=10");

		$this->addLeftMenu($this->config['top_menu_name'], "myleftmenu", "MyLeftMenu", "/myfirstmodule/index.php?test=2");

		$this->addLeftSubMenu($this->config['top_menu_name'], "myleftmenu", "mysubleftmenu", "MySubLeftMenu", "/myfirstmodule/index.php?test=3");

		$this->addLeftSubMenu($this->config['top_menu_name'], "mysubleftmenu", "", "MySecondSubLeftMenu", "/myfirstmodule/index.php?test=4");

		$this->addLeftMenu($this->config['top_menu_name'], "mysecondleftmenu", "MySecondLeftMenu", "/myfirstmodule/index.php?test=5");

		// Exports
		//--------
		
	}
}
