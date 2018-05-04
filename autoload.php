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

// Define __DIR__ for PHP version < 5.3 (should be already included in config file)
if (! defined('__DIR__')) define('__DIR__', dirname(__FILE__));

// Load Dolibarr environment
if (false === (@include_once __DIR__ . '/../main.inc.php')) {  // From htdocs directory (dolibase is in htdocs directory)
	if (false === (@include_once __DIR__ . '/../../main.inc.php')) { // From module directory (dolibase is in module directory)
		require_once __DIR__ . '/../../../main.inc.php'; // From "custom" directory (module itself is in custom directory)
	}
}

// Dolibarr detection
if (! defined('DOL_VERSION')) die('Dolibase::autoload::error Dolibarr detection failed.');

// Load Dolibase constants
require_once __DIR__ . '/const.php';

// Load Dolibase functions
require_once __DIR__ . '/core/lib/functions.php';
