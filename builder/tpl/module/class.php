<?php

// Load Dolibase config file for this module (mandatory)
dol_include_once('/${module_folder}/config.php');
// Load Dolibase Module class
${dolibase_class_include}

/**
 *	Class to describe and enable module
 */
class mod${module_class_name} extends ${dolibase_class_name}
{
	/**
	 * Function called after module configuration.
	 * 
	 */
	public function loadSettings()
	{
		${module_settings}
	}
}
