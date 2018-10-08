<?php

// Load Dolibase config file for this module (mandatory)
include_once 'config.php';
// Load Dolibase Page class
dolibase_include_once('/core/pages/create.php');

// Create Page using Dolibase
$page = new CreatePage('${page_title}', '${access_perms}');

// Set fields
// $page->fields[] = new Field(...);

// Get parameters
$action = GETPOST('action', 'alpha');

// Init object
// $object = new ...

if ($action == 'create' && $page->checkFields() && $page->checkExtraFields($object))
{
	// ...
}

$page->begin();

// Add Sub Title
$page->addSubTitle('${page_title}');

// Create form
$page->openForm();

$page->openTable(array(), 'class="border" width="100%"', true);

// ...

$page->addExtraFields($object);

$page->closeTable(true);

$page->generateFormButtons();

$page->closeForm();

$page->end();
