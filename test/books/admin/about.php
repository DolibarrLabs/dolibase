<?php

// Load Dolibase config file for this module (mandatory)
include_once '../config.php';
// Load Dolibase AboutPage class
dolibase_include_once('/core/pages/about.php');

// Create About Page using Dolibase
$page = new AboutPage('About', '$user->admin', true);

$page->begin();

$page->printModuleInformations('books.png');

$page->end();
