<?php

// Load Dolibase config file for this module (mandatory)
include_once '../config.php';
// Load Dolibase AboutPage class
dolibase_include_once('/core/pages/about.php');

// Create About Page using Dolibase
$page = new AboutPage('About', '$user->admin', ${add_extrafields_tab});

$page->begin();

$page->printModuleInformations('${picture}');

$page->end();
