<?php

// Load Dolibase config file for this module (mandatory)
include_once dirname(__FILE__) . '/../../config.php'; // this file is called from Dolibarr admin folder so we use dirname(__FILE__)
// Load Dolibase Module class
dolibase_include_once('/core/class/module.php');

/**
 *	Class to describe and enable module
 */
class modBooks extends DolibaseModule
{
	/**
	 * Function called after module configuration.
	 * 
	 */
	public function loadSettings()
	{
		$this->addPermission("read", "Read permission", "r");
		$this->addPermission("create", "Create permission", "c");
		$this->addPermission("modify", "Modify permission", "m");
		$this->addPermission("delete", "Delete permission", "d");

		$this->addTopMenu($this->config['top_menu_name'], "Books", "/books/index.php");

		$this->addLeftMenu($this->config['top_menu_name'], "mybooks", "Books", "/books/index.php");

		$this->addLeftSubMenu($this->config['top_menu_name'], "mybooks", "", "New Book", "/books/new.php");

		$this->addLeftSubMenu($this->config['top_menu_name'], "mybooks", "bookslist", "List", "/books/list.php");

		$this->addDictionary('books_dict', 'Books types');

		// Exports
		//--------
		
	}
}
