<?php
/**
 * Dolibase
 * 
 * Open source framework for Dolibarr ERP/CRM
 *
 * Copyright (c) 2018 - 2019
 *
 *
 * @package     Dolibase
 * @author      AXeL
 * @copyright   Copyright (c) 2018 - 2019, AXeL-dev
 * @license     MIT
 * @link        https://github.com/AXeL-dev/dolibase
 * 
 */

require_once DOL_DOCUMENT_ROOT . '/core/class/commondocgenerator.class.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/functions2.lib.php';

/**
 * DocModel class
 */

abstract class DocModel extends CommonDocGenerator
{
	public $error = '';

	/**
	 * Return list of active generation models
	 *
	 * @param   DoliDB      $db                     Database handler
	 * @param   integer     $maxfilenamelength      Max length of value to show
	 * @param   string      $type                   Model(s) type
	 * @return  array                               List of templates
	 */
	public static function getModelsList($db, $maxfilenamelength=0, $type = '')
	{
		$type = (! empty($type) ? $type : get_rights_class());

		$list = getListOfModels($db, $type, $maxfilenamelength);

		return $list;
	}
}
