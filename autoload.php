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

// Dolibarr detection
if (! defined('DOL_VERSION')) die('Dolibase::autoload::error Dolibarr detection failed.');

// Define __DIR__ for PHP version < 5.3 (should be already defined in config file, keep here for safety)
if (! defined('__DIR__')) define('__DIR__', dirname(__FILE__));

// Load migration scripts
include __DIR__ . '/migration/migrate_config.php';

// Load Dolibase main components (only if not already loaded by another module)
if (! defined('DOLIBASE_VERSION'))
{
	// Load Dolibase constants
	require_once __DIR__ . '/const.php';

	// Load Dolibase functions
	require_once __DIR__ . '/core/lib/functions.php';
}
