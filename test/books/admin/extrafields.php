<?php

// Load Dolibase config file for this module (mandatory)
include_once '../config.php';
// Load Dolibase AboutPage class
dolibase_include_once('/core/pages/extrafields.php');

// Create ExtraFields Page using Dolibase
$page = new ExtraFieldsPage('books');

$page->begin();

$page->end();