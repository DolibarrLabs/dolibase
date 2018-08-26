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

global $dolibase_path, $debugbar;

// Get Dolibase path
$dolibase_path = get_dolibase_path();

// Initialise Debug bar
if (DOLIBASE_ENV == 'dev' && ! is_object($debugbar)) {
	$debugbar = init_debugbar();
}
