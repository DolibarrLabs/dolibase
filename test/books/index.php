<?php

// Load Dolibarr environment (mandatory)
if (false === (@include '../../main.inc.php')) {  // From htdocs directory
	require '../../../main.inc.php'; // From "custom" directory
}

// Load Dolibase config file for this module (mandatory)
dol_include_once('/books/config.php');
// Load Dolibase Page class
dolibase_include_once('/core/class/page.php');

// Create Page using Dolibase
$page = new Page("My Page");
/*
$page->addTab("My Tab", "/books/index.php", true);

$page->setTabsPicture('books@books');
*/
$page->begin();

echo 'here';

$page->end();
