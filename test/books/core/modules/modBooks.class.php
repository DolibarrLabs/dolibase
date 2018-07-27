<?php

// Load Dolibase config file for this module (mandatory)
dol_include_once('/books/config.php');
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
		$this->addConstant("BOOKS_ADDON", "marbre");

		$this->addPermission("read", "Read permission", "r");
		$this->addPermission("create", "Create permission", "c");
		$this->addPermission("modify", "Modify permission", "m");
		$this->addPermission("delete", "Delete permission", "d");

		$this->addTopMenu($this->config['other']['top_menu_name'], "Books", "/books/index.php");

		$this->addLeftMenu($this->config['other']['top_menu_name'], "mybooks", "Books", "/books/index.php");

		$this->addLeftSubMenu($this->config['other']['top_menu_name'], "mybooks", "", "New Book", "/books/new.php");

		$this->addLeftSubMenu($this->config['other']['top_menu_name'], "mybooks", "bookslist", "List", "/books/list.php");

		$this->addDictionary('books_dict', 'Books types');

		// Exports
		//--------
		
	}
}
