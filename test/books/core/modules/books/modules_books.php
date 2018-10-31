<?php

// Load Dolibase config file for this module (mandatory)
dol_include_once('/books/config.php');
// Load Dolibase DocModel class
dolibase_include_once('/core/class/doc_model.php');

/**
 * ModeleBooks class
 *
 * This class is used to keep compatibility with dolibarr documents block
 */

abstract class ModeleBooks extends DocModel
{
	/**
	 *  Return list of active generation models
	 *
	 *  @param	DoliDB	$db     			Database handler
	 *  @param  integer	$maxfilenamelength  Max length of value to show
	 *  @return	array						List of templates
	 */
	public static function liste_modeles($db, $maxfilenamelength=0)
	{
		return parent::getModelsList($db, $maxfilenamelength);
	}
}

// Fix for dolibarr 8
abstract class ModelePDFBooks extends ModeleBooks {}
