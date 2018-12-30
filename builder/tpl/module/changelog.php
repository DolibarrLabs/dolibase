<?php

// Load Dolibase
include_once '../autoload.php';

// Load Dolibase ChangelogPage class
dolibase_include_once('core/pages/changelog.php');

// Create Changelog Page using Dolibase
$page = new ChangelogPage('Changelog', '$user->admin', ${add_extrafields_tab});

$page->begin();

$page->printChangelog();

$page->end();
