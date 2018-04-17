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
 * @copyright	Copyright (c) 2018 - 2019, AXeL-dev
 * @license
 * @link
 * 
 */


/**
 * Returns Dolibarr version using DOL_VERSION const
 *
 * This function is not really needed but i prefer to keep it anyway
 *
 * @return     string     Dolibarr version
 */
function getDolibarrVersion()
{
	return DOL_VERSION;
}

/**
 * Check if Dolibarr version if greather than another
 *
 * @param     $version     Dolibarr version to compare with
 * @return    int          1 or 0
 */
function dolibarrVersionGreatherThan($version)
{
	$dol_version = explode('.', DOL_VERSION);
	$your_version = explode('.', $version);

	return $dol_version[0] > $your_version[0] || 
			($dol_version[0] == $your_version[0] && $dol_version[1] > $your_version[1]) || 
			($dol_version[0] == $your_version[0] && $dol_version[1] == $your_version[1] && $dol_version[2] > $your_version[2]) 
			? 1 : 0;
}

/**
 * Include Dolibase components
 *
 * @param     $component_path     Dolibase component path
 */
function dolibase_include_once($component_path)
{
	global $dolibase_config;

	if (empty($dolibase_config) || empty($dolibase_config['module_folder'])) {
		die('Dolibase::Functions::Error cannot include component "'.$component_path.'" (module folder not found).');
	}

	$path = preg_replace('/^\//', '', $component_path); // Clean the path

	if (false === (@include_once DOL_DOCUMENT_ROOT.'/dolibase/'.$path)) { // From htdocs directory
		dol_include_once('/'.$dolibase_config['module_folder'].'/dolibase/'.$path); // From module directory
	}
}

/**
 * Returns posted date
 *
 * @param      $date_input_name       date input name
 * @param      $convert_to_db_format  should convert the date to database format or not
 * @return     string                 date in your db format, null if error/empty
 */
function GETPOSTDATE($date_input_name, $convert_to_db_format = false)
{
	$date = dol_mktime(12, 0, 0, GETPOST($date_input_name.'month'), GETPOST($date_input_name.'day'), GETPOST($date_input_name.'year'));

	if ($convert_to_db_format) {
		global $db;

		return empty($date) ? null : $db->idate($date);
	}
	else {
		return $date;
	}
}

/**
 * Convert empty values to null
 *
 * @return     null|string     null or initial value
 */
function empty_to_null($value)
{
	return empty($value) ? null : $value;
}

/**
 * Returns current date & time
 *
 * @param      $convert_to_db_format  should convert the date to database format or not
 * @return     string                 current date in your db format
 */
function dolibase_now($convert_to_db_format = false)
{
	global $db;

	$now = dol_now();

	return $convert_to_db_format ? $db->idate($now) : $now;
}
