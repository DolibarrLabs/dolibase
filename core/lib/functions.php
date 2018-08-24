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
 * Check if Dolibarr version if greater than another
 *
 * @param     $version     Dolibarr version to compare with
 * @return    int          1 or 0
 */
if (! function_exists('dolibarrVersionGreaterThan'))
{
	function dolibarrVersionGreaterThan($version)
	{
		return currentVersionGreaterThanVersion(DOL_VERSION, $version);
	}
}

/**
 * Check if Dolibarr version if less than another
 *
 * @param     $version     Dolibarr version to compare with
 * @return    int          1 or 0
 */
if (! function_exists('dolibarrVersionLessThan'))
{
	function dolibarrVersionLessThan($version)
	{
		return currentVersionLessThanVersion(DOL_VERSION, $version);
	}
}

/**
 * Check if a version if greater than another
 *
 * @param     $current_version     Current version
 * @param     $version             Version to compare with
 * @return    int                  1 or 0
 */
if (! function_exists('currentVersionGreaterThanVersion'))
{
	function currentVersionGreaterThanVersion($current_version, $version)
	{
		$current_version_digits = explode('.', $current_version);
		$version_digits = explode('.', $version);

		return $current_version_digits[0] > $version_digits[0] || 
		(isset($version_digits[1]) && $current_version_digits[0] == $version_digits[0] && $current_version_digits[1] > $version_digits[1]) || 
		(isset($version_digits[2]) && $current_version_digits[0] == $version_digits[0] && $current_version_digits[1] == $version_digits[1] && $current_version_digits[2] > $version_digits[2]) ? 1 : 0;
	}
}

/**
 * Check if a version if less than another
 *
 * @param     $current_version     Current version
 * @param     $version             Version to compare with
 * @return    int                  1 or 0
 */
if (! function_exists('currentVersionLessThanVersion'))
{
	function currentVersionLessThanVersion($current_version, $version)
	{
		$current_version_digits = explode('.', $current_version);
		$version_digits = explode('.', $version);

		return $current_version_digits[0] < $version_digits[0] || 
		(isset($version_digits[1]) && $current_version_digits[0] == $version_digits[0] && $current_version_digits[1] < $version_digits[1]) || 
		(isset($version_digits[2]) && $current_version_digits[0] == $version_digits[0] && $current_version_digits[1] == $version_digits[1] && $current_version_digits[2] < $version_digits[2]) ? 1 : 0;
	}
}

/**
 * Include Dolibase components
 *
 * Use it only to include Dolibase components otherwise it will not work
 *
 * @param     $component_path     Dolibase component path
 */
if (! function_exists('dolibase_include_once'))
{
	function dolibase_include_once($component_path)
	{
		global $dolibase_config;

		$path = preg_replace('/^\//', '', $component_path); // Clean the path

		if (false === (@include_once DOL_DOCUMENT_ROOT.DOLIBASE_PATH.'/'.$path)) { // @ is used to skip warnings..
			//dol_include_once('/'.$dolibase_config['module']['folder'].'/dolibase/'.$path);
			@include_once dol_buildpath('/'.$dolibase_config['module']['folder'].'/dolibase/'.$path);
		}
	}
}

/**
 * Returns posted date
 *
 * @param      $date_input_name       date input name
 * @param      $convert_to_db_format  should convert the date to database format or not
 * @return     string                 date in your db format, null if error/empty
 */
if (! function_exists('GETPOSTDATE'))
{
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
}

/**
 * Convert empty values to null
 *
 * @param      $value          value to convert
 * @param      $minus_one_also consider -1 also as an empty value
 * @return     null|string     null or initial value
 */
if (! function_exists('empty_to_null'))
{
	function empty_to_null($value, $minus_one_also = false)
	{
		return empty($value) || ($minus_one_also && $value == -1) ? null : $value;
	}
}

/**
 * Returns current date & time
 *
 * @param      $convert_to_db_format  should convert the date to database format or not
 * @return     string                 current date in your db format
 */
if (! function_exists('dolibase_now'))
{
	function dolibase_now($convert_to_db_format = false)
	{
		global $db;

		$now = dol_now();

		return $convert_to_db_format ? $db->idate($now) : $now;
	}
}

/**
 * Escape a string from ' or " to avoid errors when dealing with database
 *
 * @param      $str       string to escape
 * @return     string     escaped string
 */
if (! function_exists('str_escape'))
{
	function str_escape($str)
	{
		global $db;

		return $db->escape($str);
	}
}

/**
 * Returns price with currency
 *
 * @param      $price       price
 * @return     string       price with currency
 */
if (! function_exists('price_with_currency'))
{
	function price_with_currency($price, $currency = 'auto')
	{
		return price($price, 0, '', 1, -1, -1, $currency);
	}
}

/**
 * Returns module rights class
 *
 * @param      $to_upper           convert to upper case
 * @param      $no_underscores     remove underscores
 * @return     string              module rights class
 */
if (! function_exists('get_rights_class'))
{
	function get_rights_class($to_upper = false, $no_underscores = false)
	{
		global $dolibase_config;

		$rights_class = empty($dolibase_config['module']['rights_class']) ? 'dolibase_module' : $dolibase_config['module']['rights_class'];

		if ($to_upper) {
			$rights_class = strtoupper($rights_class);
		}

		if ($no_underscores) {
			$rights_class = str_replace('_', '', $rights_class);
		}

		return $rights_class;
	}
}
