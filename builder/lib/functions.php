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
function getPostData($name)
{
	return ! empty($name) && isset($_POST[$name]) ? $_POST[$name] : '';
}

/**
 * Convert a number to alpha
 *
 * @return string
 *     alphabet number
 */
function num2Alpha($num)
{
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
			die('BuilderError: non 0-9 numeric value provided to num2Alpha function.');
	}
}

/**
 * Convert a boolean to alpha
 *
 * @return string
 *     'true' or 'false'
 */
function bool2Alpha($bool)
{
	return $bool ? 'true' : 'false';
}

/**
 * Convert a boolean to integer
 *
 * @return integer
 *     1 or 0
 */
function bool2Int($bool)
{
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
	$parts = explode(DIRECTORY_SEPARATOR, $path);
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

	$dolibase_config = file_get_contents($root.'/dolibase/config.php');

	// Extract dolibase version
	preg_match("/'version'\s+=> '(.*)'/", $dolibase_config, $dolibase_version);

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
	$files = glob($module_path.'/core/modules/mod*.class.php');

	if (is_array($files))
	{
		foreach($files as $filename) {
			return $filename;
		}
	}

	die('BuilderError: getModuleFileName function, could not get module filename.');
}

/**
 * Return Module rights class
 *
 * @return string
 *     module rights class
 */
function getModuleRightsCLass($module_folder)
{
	$root = getDolibarrRootDirectory();
	$config_file_path = $root.'/custom/'.$module_folder.'/config.php';

	if (file_exists($config_file_path)) {
		$module_config = file_get_contents($config_file_path);

		// Extract module rights class
		preg_match("/'rights_class'\s+=> '(.*)'/", $module_config, $rights_class);
	}

	return isset($rights_class) && isset($rights_class[1]) ? $rights_class[1] : '';
}

/**
 * Return a list of directory files
 *
 * @return array
 *     list of directory files
 */
function getDirFilesList($pattern, $no_path = false, $dir_only = false)
{
	$list = array();
	$files = glob($pattern);

	if (is_array($files))
	{
		foreach($files as $filename) {
			if ($dir_only && ! is_dir($filename)) {
				continue;
			}

			$list[] = ($no_path ? basename($filename) : $filename);
		}
	}

	return $list;
}

/**
 * Return Module object class list
 *
 * @return array
 *     module object class list
 */
function getModuleObjectClassList($module_folder)
{
	$root = getDolibarrRootDirectory();
	$module_path = $root.'/custom/'.$module_folder;

	// Get object class files list
	$list = getDirFilesList($module_path.'/class/*.class.php', true);

	return $list;
}

/**
 * Return Module language folders list
 *
 * @return array
 *     module language folders list
 */
function getModuleLangList($module_folder)
{
	$root = getDolibarrRootDirectory();
	$module_path = $root.'/custom/'.$module_folder;

	// Get language folders list
	$list = getDirFilesList($module_path.'/langs/*', true, true);

	return $list;
}

/**
 * Return Module language files list
 *
 * @return array
 *     module language files list
 */
function getModuleLangFileList($module_folder, $lang_folder)
{
	$root = getDolibarrRootDirectory();
	$module_path = $root.'/custom/'.$module_folder;

	// Get language files list
	$list = getDirFilesList($module_path.'/langs/'.$lang_folder.'/*.lang', true);

	return $list;
}

/**
 * Return Class name
 *
 * @see https://stackoverflow.com/questions/7153000/get-class-name-from-file#answer-7153080
 *
 * @return string
 *     class name
 */
function getClassName($class_file)
{
	if (file_exists($class_file)) {
		$class_content = file_get_contents($class_file);

		// Extract module rights class
		preg_match("/class ([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)/", $class_content, $class_name);
	}

	return isset($class_name) && isset($class_name[1]) ? $class_name[1] : '';
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
function getTemplate($file, $hooks = array())
{
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
 * @see https://stackoverflow.com/questions/2050859/copy-entire-contents-of-a-directory-to-another-using-php#2050909
 *
 */
function recurse_copy($source, $destination, $filter = array(), $include_only = array())
{
	$dir = opendir($source);
	@mkdir($destination);

	while(false !== ( $file = readdir($dir) )) {
		if (( $file != '.' ) && ( $file != '..' )) {
			if (! in_array($file, $filter))
			{
				if ( is_dir($source . '/' . $file) ) {
					recurse_copy($source . '/' . $file, $destination . '/' . $file, $filter, $include_only);
				}
				else if (empty($include_only) || in_array($file, $include_only)) {
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
function file_replace_contents($file, $regex, $replacement, $regex_start = '/', $regex_end = '/')
{
	// Read file content
	$file_content = file_get_contents($file);

	// Do the replacements on $file_content
	$file_content = preg_replace($regex_start.$regex.$regex_end, $replacement, $file_content);

	// Write the content to a new file
	file_put_contents($file, $file_content);
}
