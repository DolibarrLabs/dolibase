<?php

// Load Dolibase
include_once '../autoload.php';
// Load Dolibase SetupPage class
dolibase_include_once('/core/pages/setup.php');

// Create Setup Page using Dolibase
$page = new SetupPage('Setup', '$user->admin');

$page->begin();

global $langs;

echo $langs->trans('NoSetupAvailable');

$page->end();