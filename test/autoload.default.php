<?php

global $dolibase_config;

$__DIR__ = dirname(__FILE__); // should not be defined as a constant 'cause many autoload files may be included at once by dolibarr

// Load module configuration (mandatory)
$dolibase_config = @include($__DIR__ . '/config.php');

// Load Dolibarr environment (mandatory)
if (false === (@include_once $__DIR__ . '/../main.inc.php')) { // From htdocs directory
	require_once $__DIR__ . '/../../main.inc.php'; // From "custom" directory
}

// Load Dolibase
if (false === (@include DOL_DOCUMENT_ROOT.'/dolibase/main.php')) { // From htdocs directory
	require dol_buildpath($dolibase_config['module']['folder'].'/dolibase/main.php'); // From module directory
}
