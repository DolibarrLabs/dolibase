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
 * Check if Dolibarr version if greater than another
 *
 * @param     $version     Dolibarr version to compare with
 * @return    int          1 or 0
 */
function dolibarrVersionGreaterThan($version)
{
	$dol_version = explode('.', DOL_VERSION);
	$your_version = explode('.', $version);

	return $dol_version[0] > $your_version[0] || 
	(isset($your_version[1]) && $dol_version[0] == $your_version[0] && $dol_version[1] > $your_version[1]) || 
	(isset($your_version[2]) && $dol_version[0] == $your_version[0] && $dol_version[1] == $your_version[1] && $dol_version[2] > $your_version[2]) ? 1 : 0;
}

/**
 * Include Dolibase components
 *
 * Use it only to include Dolibase components otherwise it will not work
 *
 * @param     $component_path     Dolibase component path
 */
function dolibase_include_once($component_path)
{
	$path = preg_replace('/^\//', '', $component_path); // Clean the path

	@include_once DOL_DOCUMENT_ROOT.DOLIBASE_DOCUMENT_ROOT.'/'.$path; // @ is used to skip warnings..
}

/**
 * Load Dolibase tables
 *
 * @param      $module       module object
 */
function dolibase_load_tables(&$module)
{
	// Load Dolibase tables
	// PS: if you wanna add more sql tables & separate them from each others, just create new folder(s) inside sql folder
	// & update the code below to fit your needs

	if (DOLIBASE_ENABLE_LOGS) {
		$module->_load_tables(DOLIBASE_DOCUMENT_ROOT.'/sql/logs/');
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
 * @param      $value          value to convert
 * @param      $minus_one_also consider -1 also as an empty value
 * @return     null|string     null or initial value
 */
function empty_to_null($value, $minus_one_also = false)
{
	return empty($value) || ($minus_one_also && $value == -1) ? null : $value;
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

/**
 * Escape a string from ' or " to avoid errors when dealing with database
 *
 * @param      $str       string to escape
 * @return     string     escaped string
 */
function str_escape($str)
{
	global $db;

	return $db->escape($str);
}

/**
 * Returns price with currency
 *
 * @param      $price       price
 * @return     string       price with currency
 */
function price_with_currency($price, $currency = 'auto')
{
	return price($price, 0, '', 1, -1, -1, $currency);
}

/**
 * Returns module rights class
 *
 * @param      $to_upper      convert to upper case
 * @return     string         module rights class
 */
function get_rights_class($to_upper = false)
{
	global $dolibase_config;

	$rights_class = empty($dolibase_config['rights_class']) ? 'dolibase_module' : $dolibase_config['rights_class'];

	return $to_upper ? strtoupper($rights_class) : $rights_class;
}
