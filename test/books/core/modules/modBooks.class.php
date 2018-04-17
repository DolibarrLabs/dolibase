<?php
/* Copyright (C) 2003-2005 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2010 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2011 Regis Houssin        <regis.houssin@capnetworks.com>
 * Copyright (C) 2011      Juanjo Menent	    <jmenent@2byte.es>
 * Copyright (C) 2013	   Philippe Grand	    <philippe.grand@atoo-net.com>
 * Copyright (C) 2018	   AXeL	    			<anass_denna@hotmail.fr>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 *	\defgroup   mymodule     Module de test
 *	\brief      Module pour gerer ...
 *	\file       htdocs/core/modules/modMyModule.class.php
 *	\ingroup    mymodule
 *	\brief      Fichier de description et activation du module mymodule
 */

include_once DOL_DOCUMENT_ROOT.'/core/modules/DolibarrModules.class.php';

// Load Dolibase config file for this module (mandatory)
dol_include_once('/books/config.php');
// Load Dolibase Module class
dolibase_include_once('/core/class/module.php');

/**
 *	Class to describe and enable module
 */
class modBooks extends DolibarrModules
{

	/**
	 *   Constructor. Define names, constants, directories, boxes, permissions
	 *
	 *   @param      DoliDB		$db      Database handler
	 */
	function __construct($db)
	{
		global $conf;

		// Create Module using Dolibase
		$module = new Module($this);

		//$module->addConstant("MY_FIRST_MODULE_CONST", "test");

		$module->addPermission("read", "Read permission", "r");
		$module->addPermission("create", "Create permission", "c");
		$module->addPermission("modify", "Modify permission", "m");
		$module->addPermission("delete", "Delete permission", "d");

		$module->addTopMenu($module->config['top_menu_name'], "Books", "/books/index.php");

		$module->addLeftMenu($module->config['top_menu_name'], "mybooks", "Books", "/books/index.php");

		$module->addLeftSubMenu($module->config['top_menu_name'], "mybooks", "", "New Book", "/books/new.php");

		$module->addLeftSubMenu($module->config['top_menu_name'], "mybooks", "bookslist", "List", "/books/list.php");

		// Exports
		//--------
		
	}


	/**
	 * Function called when module is enabled.
	 * The init function add constants, boxes, permissions and menus
	 * (defined in constructor) into Dolibarr database.
	 * It also creates data directories
	 *
	 * @param string $options Options when enabling module ('', 'noboxes')
	 * @return int 1 if OK, 0 if KO
	 */
	public function init($options = '')
	{
		//$this->remove($options);

		$sql = array();

		$result = $this->loadTables();

		return $this->_init($sql, $options);
	}

	/**
	 * Create tables, keys and data required by module
	 * Files llx_table1.sql, llx_table1.key.sql llx_data.sql with create table, create keys
	 * and create data commands must be stored in directory /mymodule/sql/
	 * This function is called by this->init
	 *
	 * @return int <=0 if KO, >0 if OK
	 */
	private function loadTables()
	{
		global $dolibase_config;

		return $this->_load_tables('/'.$dolibase_config['module_folder'].'/sql/');
	}

	/**
	 * Function called when module is disabled.
	 * Remove from database constants, boxes and permissions from Dolibarr database.
	 * Data directories are not deleted
	 *
	 * @param string $options Options when enabling module ('', 'noboxes')
	 * @return int 1 if OK, 0 if KO
	 */
	public function remove($options = '')
	{
		$sql = array();

		return $this->_remove($sql, $options);
	}
}
