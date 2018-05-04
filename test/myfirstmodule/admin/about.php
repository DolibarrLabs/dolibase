<?php

// Load Dolibase config file for this module (mandatory)
include_once '../config.php';
// Load Dolibase AboutPage class
dolibase_include_once('/core/pages/about.php');

// Create About Page using Dolibase
$page = new AboutPage();

$page->begin();

$page->printModuleInformations('first.png');

$page->end();
