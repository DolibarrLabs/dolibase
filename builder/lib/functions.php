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
 * Return a posted data
 *
 * @return string
 *     posted data
 *     empty string if data name doesn't exist
 */
function getPostData($name) {
	return ! empty($name) && isset($_POST[$name]) ? $_POST[$name] : '';
}

/**
 * Convert a number to alpha
 *
 * @return string
 *     alphabet number
 */
function num2Alpha($num) {
	switch ($num) {
		case 0:
			return 'Zero';
		case 1:
			return 'One';
		case 2:
			return 'Two';
		case 3:
			return 'Three';
		case 4:
			return 'Four';
		case 5:
			return 'Five';
		case 6:
			return 'Six';
		case 7:
			return 'Seven';
		case 8:
			return 'Eight';
		case 9:
			return 'Nine';
		default:
			die('Dolibase::Builder::Error non numeric value provided to num2Alpha function.');
	}
}

/**
 * Convert a boolean to alpha
 *
 * @return string
 *     'true' or 'false'
 */
function bool2Alpha($bool) {
	return $bool ? 'true' : 'false';
}

/**
 * Convert a boolean to integer
 *
 * @return integer
 *     1 or 0
 */
function bool2Int($bool) {
	return $bool ? 1 : 0;
}

/**
 * Sanitize specified string
 *
 * @return string
 *     sanitized string
 */
function sanitizeString($str, $no_underscores = false)
{
	$sanitized_str = str_replace(' ', '', $str);

	if ($no_underscores) {
		return str_replace('_', '', $sanitized_str);
	}

	return $sanitized_str;
}

/**
 * Return Dolibarr root directory
 *
 * @return string
 *     dolibarr root directory
 */
function getDolibarrRootDirectory()
{
	$path = defined(__DIR__) ? __DIR__ : dirname(__FILE__); // should be: .../dolibarr/dolibase/builder/lib
	$parts = explode('/', $path);
	array_splice($parts, -3); // remove '/dolibase/builder/lib'
	$root_path = implode('/', $parts);

	return $root_path;
}

/**
 * Return Dolibase version
 *
 * @return string
 *     dolibase version
 */
function getDolibaseVersion($root = '')
{
	if (empty($root)) {
		$root = getDolibarrRootDirectory();
	}

	$dolibase_config_file = file_get_contents($root.'/dolibase/config.php');

	// Extract dolibase version
	preg_match("/'version'             => '(.*)'/", $dolibase_config_file, $dolibase_version);

	return isset($dolibase_version[1]) ? $dolibase_version[1] : '';
}

/**
 * Return Module filename (with path)
 *
 * @return string
 *     module filename
 */
function getModuleFileName($module_path)
{
	foreach(glob($module_path.'/core/modules/mod*.class.php') as $filename) {
		return $filename;
	}

	die('Dolibase::Builder::Error getModuleFileName function, could not get module filename.');
}

/**
 * Return external modules list (those in custom directory),
 * sorted by the last time they was changed (DESC).
 *
 * @return array
 *     modules list
 */
function getModulesList($root = '')
{
	if (empty($root)) {
		$root = getDolibarrRootDirectory();
	}

	$path = $root.'/custom';
	$dirs = array();

	// directory handle
	$dir = dir($path);

	// Get modules directories
	while (false !== ($entry = $dir->read())) {
		if ($entry != '.' && $entry != '..') {
			$entry_path = $path . '/' .$entry;
			if (is_dir($entry_path)) {
				$dirs[] = array(
					'name' => $entry,
					'ctime' => filectime($entry_path)
				);
			}
		}
	}

	// Sort directories
	sortArrayByKey($dirs, 'ctime', SORT_DESC);

	$modules_list = array();

	foreach ($dirs as $directory) {
		$modules_list[] = $directory['name'];
	}

	return $modules_list;
}

/**
 * Sort an array by key.
 *
 */
function sortArrayByKey(&$array, $array_key, $sort_order = SORT_ASC)
{
	$sortarray = array();

	foreach ($array as $key => $row) {
		$sortarray[$key] = $row[$array_key];
	}

	array_multisort($sortarray, $sort_order, $array);
}

/**
 * Return file template as a string
 *
 * @see https://stackoverflow.com/questions/26962791/load-file-as-string-which-contains-variable-definitions
 *
 * @return string
 *     template
 */
function getTemplate($file, $hooks = array()) {
	// Read our template in as a string.
	$template = file_get_contents($file);

	if (is_array($hooks) && ! empty($hooks))
	{
		$keys = array();
		$data = array();
		foreach($hooks as $key => $value) {
			array_push($keys, '${'. $key .'}');
			array_push($data, $value);
		}

		// Replace all of the variables with the variable values.
		$template = str_replace($keys, $data, $template);
	}

	return $template;
}

/**
 * Return file extension
 *
 * @return string
 *     file extension
 */
function getFileExtension($filename)
{
	$ext = pathinfo($filename, PATHINFO_EXTENSION);

	return $ext;
}

/**
 * Insert a string before file extension
 *
 * @return string
 *     filename
 */
function InsertBeforeFileExtension($filename, $str)
{
	if (empty($str)) {
		return $filename;
	}

	$extension_pos = strrpos($filename, '.'); // find position of the last dot, so where the extension starts
	return substr($filename, 0, $extension_pos) . $str . substr($filename, $extension_pos);
}

/**
 * Get author informations from 'author.json' file if found.
 *
 * @return array
 *     author informations or empty array
 */
function getAuthorInfo()
{
	$info = array();
	$root = getDolibarrRootDirectory();
	$json = @file_get_contents($root.'/dolibase/builder/author.json');

	if ($json !== false) {
		$info = json_decode($json, true);
	}

	return $info;
}

/**
 * Changes permissions on files and directories within $dir and dives recursively into found subdirectories.
 *
 * @see https://stackoverflow.com/questions/9262622/set-permissions-for-all-files-and-folders-recursively
 *
 */
function chmod_r($dir, $dirPermissions, $filePermissions)
{
	$dp = opendir($dir);
	while($file = readdir($dp)) {
		if (($file == ".") || ($file == ".."))
			continue;

		$fullPath = $dir."/".$file;

		if(is_dir($fullPath)) {
			@chmod($fullPath, $dirPermissions);
			@chmod_r($fullPath, $dirPermissions, $filePermissions);
		} else {
			@chmod($fullPath, $filePermissions);
		}
	}
	closedir($dp);
}

/**
 * Create folder(s) recursively.
 *
 * @see https://stackoverflow.com/questions/3997641/why-cant-php-create-a-directory-with-777-permissions
 *
 * @return boolean
 *     true if success
 *     false if error
 */
function mkdir_r($folders, $perm_code = 0777, $path_prefix = '')
{
	if (! empty($path_prefix) && substr($path_prefix, -1) != '/') {
		$path_prefix .= '/';
	}

	$old = umask(0);
	foreach ($folders as $folder) {
		if (! @mkdir($path_prefix.$folder, $perm_code, true)) {
			return false;
		}
	}
	umask($old);

	return true;
}

/**
 * Copy entire contents of a directory to another.
 *
 * @see https://stackoverflow.com/questions/2050859/copy-entire-contents-of-a-directory-to-another-using-php
 *
 */
function recurse_copy($source, $destination, $filter = array())
{
	$dir = opendir($source);
	@mkdir($destination);

	while(false !== ( $file = readdir($dir) )) {
		if (( $file != '.' ) && ( $file != '..' )) {
			if (! in_array($file, $filter))
			{
				if ( is_dir($source . '/' . $file) ) {
					recurse_copy($source . '/' . $file, $destination . '/' . $file);
				}
				else {
					copy($source . '/' . $file, $destination . '/' . $file);
				}
			}
		}
	}

	closedir($dir); 
}

/**
 * Replace contents of a file.
 *
 */
function file_replace_contents($file, $str_to_replace, $replace_with_str)
{
	// Read file content
	$file_content = file_get_contents($file);

	// Do the replacements on $file_content
	$file_content = str_replace($str_to_replace, $replace_with_str, $file_content);
	//$file_content = preg_replace('/'.$str_to_replace.'/', $replace_with_str, $file_content);

	// Write the content to a new file
	file_put_contents($file, $file_content);
}
