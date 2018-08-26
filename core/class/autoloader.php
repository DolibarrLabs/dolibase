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
			$file = dolibase_buildpath('/extra/'.str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php');
			if (file_exists($file)) {
				require_once $file;
				return true;
			}
			return false;
		});
	}
}