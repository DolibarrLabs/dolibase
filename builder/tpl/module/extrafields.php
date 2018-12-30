<?php

// Load Dolibase
include_once '../autoload.php';

// Load Dolibase Page class
dolibase_include_once('core/pages/extrafields.php');

// Create ExtraFields Page using Dolibase
$page = new ExtraFieldsPage('${element_type}', 'ExtraFields', '$user->admin', ${add_changelog_tab});

$page->begin();

$page->printExtraFields();

$page->end();
