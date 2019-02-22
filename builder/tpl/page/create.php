<?php

// Load Dolibase
require_once 'autoload.php';

// Load Dolibase Page class
dolibase_include_once('core/pages/create.php');

// Load Object class
${object_class_include}

// Create Page using Dolibase
$page = new CreatePage('${page_title}', '${access_perms}');

// Get parameters
$action = GETPOST('action', 'alpha');

// Init object
${object_init}

// Set fields
//$page->setFields(array(
//	new Field(...)
//));

// Create object
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
