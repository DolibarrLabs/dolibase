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
 * @param     $sign           Compare sign, possible values: '>', '>=', '<', '<='
 * @param     $version_to     Version to compare with
 * @return    boolean         true or false
 */
if (! function_exists('compare_version'))
{
	function compare_version($version, $sign, $version_to)
	{
		$version_digits = explode('.', $version);
		$version_to_digits = explode('.', $version_to);

		if (! in_array($sign, array('>', '>=', '<', '<=')))
		{
			dolibase_error('Wrong sign='.$sign.' provided to '.__FUNCTION__, true);
		}

		// 1st - try using built-in dolibarr function
		else if (function_exists('versioncompare'))
		{
			$result = versioncompare($version_digits, $version_to_digits);

			if ($sign == '>') {
				return ($result > 0);
			}
			else if ($sign == '>=') {
				return ($result >= 0);
			}
			else if ($sign == '<') {
				return ($result < 0);
			}
			else if ($sign == '<=') {
				return ($result <= 0);
			}
		}

		// 2nd - try using dolibase own implementation
		else if ($sign == '>' || $sign == '>=')
		{
			$greater_than = $version_digits[0] > $version_to_digits[0] || 
			(isset($version_to_digits[1]) && $version_digits[0] == $version_to_digits[0] && $version_digits[1] > $version_to_digits[1]) || 
			(isset($version_to_digits[2]) && $version_digits[0] == $version_to_digits[0] && $version_digits[1] == $version_to_digits[1] && $version_digits[2] > $version_to_digits[2]) ? true : false;

			return ($sign == '>=' ? (($version == $version_to) || $greater_than) : $greater_than);
		}
		else if ($sign == '<' || $sign == '<=')
		{
			$lesser_than = $version_digits[0] < $version_to_digits[0] || 
			(isset($version_to_digits[1]) && $version_digits[0] == $version_to_digits[0] && $version_digits[1] < $version_to_digits[1]) || 
			(isset($version_to_digits[2]) && $version_digits[0] == $version_to_digits[0] && $version_digits[1] == $version_to_digits[1] && $version_digits[2] < $version_to_digits[2]) ? true : false;

			return ($sign == '<=' ? (($version == $version_to) || $lesser_than) : $lesser_than);
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
 * @return    string              File path or url
 */
if (! function_exists('dolibase_buildpath'))
{
	function dolibase_buildpath($component_path, $as_url = false)
	{
		global $dolibase_config;

		$path = preg_replace('/^\//', '', $component_path); // Clean the path

		if (preg_match('/^\/?dolibase$/', $dolibase_config['main']['path'])) {
			return ($as_url ? DOL_URL_ROOT : DOL_DOCUMENT_ROOT).'/dolibase/'.$path;
		}

		return dol_buildpath($dolibase_config['main']['path'].'/'.$path, ($as_url ? 1 : 0));
	}
}

/**
 * Return Dolibase components full url (alias for dolibase_buildpath function)
 *
 * @param     $component_path     Dolibase component path
 * @return    string              File url
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
 * @return     string     Dolibase relative path as 'dolibase' or 'module/dolibase'
 */
if (! function_exists('get_dolibase_path'))
{
	function get_dolibase_path()
	{
		if (file_exists(DOL_DOCUMENT_ROOT . '/dolibase')) {
			return 'dolibase';
		}
		else {
			global $dolibase_config;

			return $dolibase_config['module']['folder'].'/dolibase';
		}
	}
}

/**
 * Check if a value have been submitted by GET or POST method
 *
 * @param      $value_name       value name
 * @return     boolean           true or false
 */
if (! function_exists('is_submitted'))
{
	function is_submitted($value_name)
	{
		return isset($_GET[$value_name]) || isset($_POST[$value_name]);
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
		if (is_submitted($date_input_name.'month') && is_submitted($date_input_name.'day') && is_submitted($date_input_name.'year')) {
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
 * Return posted datetime
 *
 * @since      2.9.4
 * @param      $datetime_input_name   datetime input name
 * @param      $convert_to_db_format  should convert the datetime to database format or not
 * @return     string                 datetime in your db format, null if error/empty
 */
if (! function_exists('GETPOSTDATETIME'))
{
	function GETPOSTDATETIME($datetime_input_name, $convert_to_db_format = false)
	{
		if (is_submitted($datetime_input_name.'hour') && is_submitted($datetime_input_name.'min') && is_submitted($datetime_input_name.'month') && is_submitted($datetime_input_name.'day') && is_submitted($datetime_input_name.'year')) {
			$date = dol_mktime(GETPOST($datetime_input_name.'hour'), GETPOST($datetime_input_name.'min'), 0, GETPOST($datetime_input_name.'month'), GETPOST($datetime_input_name.'day'), GETPOST($datetime_input_name.'year'));
		}
		else {
			$date = GETPOST($datetime_input_name);
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
 * Output date in a string format
 *
 * @since      2.8.1
 * @param      $date            date in db format or GM Timestamps date if $convert_to_tms is false
 * @param      $format          output date format (tag of strftime function)
 *                              "%d %b %Y",
 *                              "%d/%m/%Y %H:%M",
 *                              "%d/%m/%Y %H:%M:%S",
 *                              "%B"=Long text of month, "%A"=Long text of day, "%b"=Short text of month, "%a"=Short text of day
 *                              "day", "daytext", "dayhour", "dayhourldap", "dayhourtext", "dayrfc", "dayhourrfc", "...reduceformat"
 * @param      $convert_to_tms  convert the $date parameter to timestamp or not
 * @return     string           formated date or '' if date is null
 */
if (! function_exists('dolibase_print_date'))
{
	function dolibase_print_date($date, $format, $convert_to_tms = true)
	{
		global $db;

		$time = $convert_to_tms ? $db->jdate($date) : $date;

		return dol_print_date($time, $format);
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
 * Return module part (which is the same as module rights class in general)
 *
 * @return     string     module part
 */
if (! function_exists('get_modulepart'))
{
	function get_modulepart()
	{
		return get_rights_class(false, true);
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
 * Add a flash message to session.
 * Note: message rendering will be done by $page->end() or more exactly llxFooter() function
 *
 * @param     $message     Message
 * @param     $type        Message type, possible values: 'success' by default, 'warning', 'error'
 */
if (! function_exists('dolibase_flash_message'))
{
	function dolibase_flash_message($message, $type = 'success')
	{
		global $langs;

		$types_array = array(
			'success' => 'mesgs',
			'warning' => 'warnings',
			'error'   => 'errors'
		);

		if (! isset($types_array[$type])) {
			dolibase_error('Bad parameter type='.$type.' provided to '.__FUNCTION__);
		} else if (! empty($message)) {
			setEventMessage($langs->trans($message), $types_array[$type]);
		}
	}
}

/**
 * Displays error message with all the information to facilitate the diagnosis.
 *
 * @param     $error       Error message
 * @param     $die         Use PHP die() function instead of dol_print_error()
 */
if (! function_exists('dolibase_error'))
{
	function dolibase_error($error, $die = false)
	{
		$error_message = 'DolibaseError: '.$error;

		if ($die) {
			die($error_message);
		} else {
			dol_print_error('', $error_message);
		}
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

		if (is_object($debugbar))
		{
			if (! empty($stop_name)) {
				stop_time_measure($stop_name);
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

		if (is_object($debugbar) && $debugbar['time']->hasStartedMeasure($name)) {
			$debugbar['time']->stopMeasure($name);
		}
	}
}

/**
 * Dumps all the object propreties and its associations recursively into an array
 *
 * @see http://php.net/manual/fr/function.get-object-vars.php#62470
 * @param     $obj     Object
 * @return    array    Object as an array
 */
if (! function_exists('object_to_array'))
{
	function object_to_array($obj)
	{
		$_arr = is_object($obj) ? get_object_vars($obj) : $obj;
		$arr = array();

		foreach ($_arr as $key => $val) {
			$val = (is_array($val) || is_object($val)) ? object_to_array($val) : $val;
			$arr[$key] = $val;
		}

		return $arr;
	}
}

/**
 * Create an HTML table from a two-dimensional array
 *
 * @since     2.8.0
 * @param     $array           array
 * @param     $show_header     show table header or not
 * @return    string           HTML table
 */
if (! function_exists('array_to_table'))
{
	function array_to_table($array, $show_header = true)
	{
		$out = '<table class="liste">';
		$count = 0;

		foreach ($array as $row)
		{
			if ($count == 0 && $show_header)
			{
				$out.= '<tr class="liste_titre">';
				foreach ((array)$row as $key => $value) {
					$out.= '<th><strong>'.$key.'</strong></th>';
				}
				$out.= '</tr>';
			}

			$out.= '<tr>';
			foreach ((array)$row as $value) {
				$out.= '<td>'.$value.'</td>';
			}
			$out.= '</tr>';

			$count++;
		}

		$out.= '</table>';

		return $out;
	}
}

/**
 * Loops on an array values & check if a value matches the pattern using preg_match
 *
 * @since     2.8.5
 * @param     $pattern   Regular expression pattern
 * @param     $array     Array to check
 * @param     $matches   Pattern matches
 * @return    int|bool   1 if the pattern matches given array, 0 if it does not, or FALSE if an error occurred.
 */
if (! function_exists('array_match'))
{
	function array_match($pattern, $array, &$matches)
	{
		foreach ($array as $value)
		{
			$result = preg_match($pattern, $value, $matches);
			if ($result) {
				return $result;
			}
		}

		return 0;
	}
}

/**
 * Converts an array values to string separated by a delimiter
 *
 * @since     2.9.3
 * @param     $array     Array
 * @param     $delimiter Values delimiter
 * @return    string     array values string separated by the delimiter or empty string if array is empty
 */
if (! function_exists('array_to_string'))
{
	function array_to_string($array, $delimiter = ',')
	{
		return (is_array($array) && ! empty($array) ? join($delimiter, $array) : '');
	}
}

/**
 * Converts a string into an array using a delimiter to separate/get the values
 *
 * @since     2.9.3
 * @param     $str       String
 * @param     $delimiter Values delimiter
 * @return    array      array filled with values from string as ['value' => 'value'] or empty array if string is empty
 */
if (! function_exists('string_to_array'))
{
	function string_to_array($str, $delimiter = ',')
	{
		$arr = array();

		if (! empty($str))
		{
			foreach (explode($delimiter, $str) as $value) {
				$trimed_value = trim($value);
				$arr[$trimed_value] = $trimed_value;
			}
		}

		return $arr;
	}
}

/**
 * Returns if javascript/jquery is enabled
 *
 * @since     2.9.3
 * @return    boolean    true if javascript is enabled, else false
 */
if (! function_exists('js_enabled'))
{
	function js_enabled()
	{
		global $conf;

		return (! empty($conf->use_javascript_ajax) && empty($conf->dol_use_jmobile));
	}
}

/**
 * Return current module path
 *
 * @param     $as_url    Return path as url
 * @return    string     Module path
 */
if (! function_exists('get_module_path'))
{
	function get_module_path($as_url = false)
	{
		global $dolibase_config;

		return dol_buildpath($dolibase_config['module']['folder'], ($as_url ? 1 : 0));
	}
}
