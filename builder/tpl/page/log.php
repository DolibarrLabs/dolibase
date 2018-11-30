<?php

// Load Dolibase
include_once 'autoload.php';

// Load Dolibase Page class
dolibase_include_once('/core/pages/log.php');

// Load Object class
${object_class_include}

// Create Page using Dolibase
$page = new LogPage('${page_title}', '${access_perms}');

// Get parameters
$id = GETPOST('id', 'int');
$ref = GETPOST('ref', 'alpha');

// Init object
${object_init}

if (($id > 0 || ! empty($ref)) && $object->fetch($id, $ref))
{
	// Add tabs
	${tabs}

	$page->begin();

	// Logs
	$page->printLogs($object->id);
}
else $page->begin()->notFound();

$page->end();
