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


/**
 * Compare two versions
 *
 * @param     $version        Version string, possible values: 'x', 'x.x', 'x.x.x'
 * @param     $sign           Compare sign, possible values: '>', '<'
 * @param     $version_to     Version to compare with
 * @return    boolean         true or false
 */
if (! function_exists('compare_version'))
{
	function compare_version($version, $sign, $version_to)
	{
		$version_digits = explode('.', $version);
		$version_to_digits = explode('.', $version_to);

		if ($sign == '>') {
			return $version_digits[0] > $version_to_digits[0] || 
			(isset($version_to_digits[1]) && $version_digits[0] == $version_to_digits[0] && $version_digits[1] > $version_to_digits[1]) || 
			(isset($version_to_digits[2]) && $version_digits[0] == $version_to_digits[0] && $version_digits[1] == $version_to_digits[1] && $version_digits[2] > $version_to_digits[2]) ? true : false;
		}
		else if ($sign == '<') {
			return $version_digits[0] < $version_to_digits[0] || 
			(isset($version_to_digits[1]) && $version_digits[0] == $version_to_digits[0] && $version_digits[1] < $version_to_digits[1]) || 
			(isset($version_to_digits[2]) && $version_digits[0] == $version_to_digits[0] && $version_digits[1] == $version_to_digits[1] && $version_digits[2] < $version_to_digits[2]) ? true : false;
		}
		else {
			die('Dolibase::Functions::Error wrong sign provided to '.__FUNCTION__.'.');
		}
	}
}

/**
 * Include Dolibase components
 *
 * Use it only to include Dolibase components otherwise it will not work
 *
 * @param     $component_path     Dolibase component path
 * @param     $class_name         Dolibase class name that should be checked before including file,
 *                                to bypass => Fatal error: Cannot declare class xxx, because the name is already in use
 */
if (! function_exists('dolibase_include_once'))
{
	function dolibase_include_once($component_path, $class_name = '')
	{
		if (empty($class_name) || ! class_exists($class_name)) {
			@include_once dolibase_buildpath($component_path); // @ is used to skip warnings..
		}
	}
}

/**
 * Return Dolibase components full path
 *
 * Use it only for Dolibase components otherwise it will not work
 *
 * @param     $component_path     Dolibase component path
 * @param     $as_url             Return path as url
 */
if (! function_exists('dolibase_buildpath'))
{
	function dolibase_buildpath($component_path, $as_url = false)
	{
		global $dolibase_config;

		$path = preg_replace('/^\//', '', $component_path); // Clean the path

		if ($dolibase_config['main']['path'] == '/dolibase') {
			return ($as_url ? DOL_URL_ROOT : DOL_DOCUMENT_ROOT).'/dolibase/'.$path;
		}

		return dol_buildpath($dolibase_config['main']['path'].'/'.$path, ($as_url ? 1 : 0));
	}
}

/**
 * Return Dolibase components full url (alias for dolibase_buildpath function)
 *
 * @param     $component_path     Dolibase component path
 */
if (! function_exists('dolibase_buildurl'))
{
	function dolibase_buildurl($component_path)
	{
		return dolibase_buildpath($component_path, true);
	}
}

/**
 * Return Dolibase relative path
 *
 * Possible values: '/dolibase', '/module/dolibase'
 */
if (! function_exists('get_dolibase_path'))
{
	function get_dolibase_path()
	{
		if (file_exists(DOL_DOCUMENT_ROOT . '/dolibase')) {
			return '/dolibase';
		}
		else {
			global $dolibase_config;

			return '/'.$dolibase_config['module']['folder'].'/dolibase';
		}
	}
}

/**
 * Return posted date
 *
 * @param      $date_input_name       date input name
 * @param      $convert_to_db_format  should convert the date to database format or not
 * @return     string                 date in your db format, null if error/empty
 */
if (! function_exists('GETPOSTDATE'))
{
	function GETPOSTDATE($date_input_name, $convert_to_db_format = false)
	{
		if (isset($_GET[$date_input_name.'month']) || isset($_POST[$date_input_name.'month'])) { // checking month value should be sufficient
			$date = dol_mktime(0, 0, 0, GETPOST($date_input_name.'month'), GETPOST($date_input_name.'day'), GETPOST($date_input_name.'year'));
		}
		else {
			$date = GETPOST($date_input_name);
		}

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
 * Return current date & time
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
 * Return price with currency
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
 * Return module rights class
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

/**
 * Return function output as a string
 *
 * @param      $func       function name
 * @param      $args       function arguments
 */
if (! function_exists('get_func_output'))
{
	function get_func_output($func, $args = array())
	{
		ob_start();
		call_user_func_array($func, $args);
		$out = ob_get_contents();
		ob_end_clean();

		return $out;
	}
}

/**
 * Add a message to Debug bar
 *
 * @param     $message     Message
 * @param     $label       Label, possible values: 'info', 'error', 'warning', ...
 */
if (! function_exists('dolibase_debug'))
{
	function dolibase_debug($message, $label = 'info')
	{
		global $debugbar;

		if (is_object($debugbar)) {
			$debugbar['messages']->addMessage($message, $label);
		}
	}
}

/**
 * Add an exception to Debug bar
 *
 * @param     $exception     Exception object
 */
if (! function_exists('dolibase_exception'))
{
	function dolibase_exception($exception)
	{
		global $debugbar;

		if (is_object($debugbar)) {
			$debugbar['exceptions']->addException($exception);
		}
	}
}

/**
 * Redirect to specific url
 *
 * @param     $url     Url
 */
if (! function_exists('dolibase_redirect'))
{
	function dolibase_redirect($url)
	{
		global $debugbar;

		if (is_object($debugbar)) {
			$debugbar->stackData();
		}

		header('Location: ' . $url);

		exit();
	}
}

/**
 * Start time measure, that will appear later on Debug bar Timeline
 *
 * @param     $name        measure name, used to stop measure later
 * @param     $label       measure label, will appear on Timeline
 * @param     $stop_name   name of measure to stop before starting the new one, leave empty if not
 */
if (! function_exists('start_time_measure'))
{
	function start_time_measure($name, $label = '', $stop_name = '')
	{
		global $debugbar;

		if (is_object($debugbar)) {
			if (! empty($stop_name)) {
				$debugbar['time']->stopMeasure($stop_name);
			}
			$debugbar['time']->startMeasure($name, $label);
		}
	}
}

/**
 * Stop time measure
 *
 * @param     $name        measure name
 */
if (! function_exists('stop_time_measure'))
{
	function stop_time_measure($name)
	{
		global $debugbar;

		if (is_object($debugbar)) {
			$debugbar['time']->stopMeasure($name);
		}
	}
}

/**
 * Dumps all the object propreties and its associations recursively into an array
 *
 * @param     $obj     Object
 * @return    array
 */
if (! function_exists('object_to_array'))
{
	function object_to_array($obj)
	{
		$_arr = is_object($obj) ? get_object_vars($obj) : $obj;
		foreach ($_arr as $key => $val) {
			$val = (is_array($val) || is_object($val)) ? object_to_array($val) : $val;
			$arr[$key] = $val;
		}

		return $arr;
	}
}
