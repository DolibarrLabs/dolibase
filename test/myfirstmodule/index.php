<?php

// Load Dolibarr environment (mandatory)
if (false === (@include '../../main.inc.php')) {  // From htdocs directory
	require '../../../main.inc.php'; // From "custom" directory
}

// Load Dolibase config file for this module (mandatory)
dol_include_once('/myfirstmodule/config.php');
// Load Dolibase Page class
dolibase_include_once('/core/class/page.php');

// Create Page using Dolibase
$page = new Page("My Page");

//$page->addTab("My Tab", "/myfirstmodule/index.php", true);

//$page->setTabsPicture('mywidget@myfirstmodule');

$page->begin();

global $conf;

echo $conf->global->MY_FIRST_MODULE_CONST;

$page->end();
