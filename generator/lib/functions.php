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
			die('Dolibase::Generator::Error non numeric value provided to num2Alpha function.');
	}
}

/**
 * Convert a boolean to alpha
 *
 * @return string
 *     alphabet boolean
 */
function bool2Alpha($bool) {
	return $bool ? 'true' : 'false';
}

/**
 * Return Dolibarr root directory
 *
 * @return string
 *     dolibarr root directory
 */
function getDolibarrRootDirectory()
{
	$path = defined(__DIR__) ? __DIR__ : dirname(__FILE__); // should be: .../dolibarr/dolibase/generator/lib
	$parts = explode('/', $path);
	array_splice($parts, -3); // remove '/dolibase/generator/lib'
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
 * Return file template as a string
 *
 * @see https://stackoverflow.com/questions/26962791/load-file-as-string-which-contains-variable-definitions
 *
 * @return string
 *     template
 */
function getTemplate($file, $hooks) {
	// Read our template in as a string.
	$template = file_get_contents($file);

	$keys = array();
	$data = array();
	foreach($hooks as $key => $value) {
		array_push($keys, '${'. $key .'}');
		array_push($data,  $value );
	}

	// Replace all of the variables with the variable values.
	$template = str_replace($keys, $data, $template);

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
			chmod($fullPath, $dirPermissions);
			chmod_r($fullPath, $dirPermissions, $filePermissions);
		} else {
			chmod($fullPath, $filePermissions);
		}
	}
	closedir($dp);
}

/**
 * Copy entire contents of a directory to another.
 *
 * @see https://stackoverflow.com/questions/2050859/copy-entire-contents-of-a-directory-to-another-using-php
 *
 */
function recurse_copy($source, $destination, $exclude = array())
{
	$dir = opendir($source);
	@mkdir($destination);
	while(false !== ( $file = readdir($dir) )) {
		if (( $file != '.' ) && ( $file != '..' )) {
			if (! in_array($file, $exclude))
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

	// write the content to a new file
	file_put_contents($file, $file_content);
}
