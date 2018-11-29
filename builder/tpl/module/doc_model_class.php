<?php

// Load Dolibase
dol_include_once('/${module_folder}/autoload.php');

// Load Dolibase DocModel class
dolibase_include_once('/core/class/doc_model.php');

/**
 * Modele${model_class} class
 *
 * This class is used to keep compatibility with dolibarr documents block
 */

abstract class Modele${model_class} extends DocModel
{
	/**
	 *  Return list of active generation models
	 *
	 *  @param  DoliDB  $db                 Database handler
	 *  @param  integer $maxfilenamelength  Max length of value to show
	 *  @return array                       List of templates
	 */
	public static function liste_modeles($db, $maxfilenamelength=0)
	{
		return parent::getModelsList($db, $maxfilenamelength);
	}
}

// FIX for Dolibarr 8
abstract class ModelePDF${model_class} extends Modele${model_class} {}
