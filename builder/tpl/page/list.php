<?php

// Load Dolibase
include_once 'autoload.php';

// Load Dolibase Page class
dolibase_include_once('/core/pages/list.php');

// Load Object class
${object_class_include}

// Create Page using Dolibase
$page = new ListPage('${page_title}', '${access_perms}');

// Get parameters
global $conf;
$sortorder = GETPOST('sortorder', 'alpha') ? GETPOST('sortorder', 'alpha') : 'DESC';
$sortfield = GETPOST('sortfield', 'alpha') ? GETPOST('sortfield', 'alpha') : 't.creation_date';
$limit = GETPOST('limit') ? GETPOST('limit', 'int') : $conf->liste_limit;
$offset = $limit * (GETPOST('page', 'int') ? GETPOST('page', 'int') : 0);

// search parameters
$search = array();
// ...

// Init object
${object_init}

$page->begin();

// Adjust query
$fieldstosearchall = array();
$where = '1=1';
// ...

// Fetch extrafields
$more_fields = '';
$join = '';
$page->fetchExtraFields($object->table_element, $more_fields, $join, $where);

// Fetch
$object->fetchAll($limit, $offset, $sortfield, $sortorder, $more_fields, $join, $where, true);

// List fields
$list_fields = array();
// $list_fields[] = array(...);

// Print list head
$page->openList('${page_title}', 'title_generic.png', $list_fields, $search, $object->count, $object->total, $fieldstosearchall, $sortfield, $sortorder);

$odd = true;

// Print lines
foreach ($object->lines as $obj)
{
	$odd = !$odd;
	$page->openRow($odd);

	// Column
	//$page->addColumn(...);

	// Extrafields
	$page->addExtraFields($obj);

	$page->closeRow();
}

// close list
$page->closeList();

$page->end();
