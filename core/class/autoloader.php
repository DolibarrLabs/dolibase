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
 * Autoloader class
 *
 * Simple autoloader, so we don't need Composer just for this.
 */

class Autoloader
{
	/**
	 * Register autoloader
	 *
	 */
	public static function register()
	{
		spl_autoload_register(function ($class) {
			$root = DOL_DOCUMENT_ROOT.DOLIBASE_PATH.'/extra/';
			$file = $root.str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php';
			if (file_exists($file)) {
				require $file;
				return true;
			}
			return false;
		});
	}
}