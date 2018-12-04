<?php

// Load Dolibase
include_once 'autoload.php';

// Load Dolibase Page class
dolibase_include_once('/core/pages/list.php');

// Load Object class
${object_class_include}

// Load Dolibase QueryBuilder class
dolibase_include_once('/core/class/query_builder.php');

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

// Fetch
$qb = new QueryBuilder();
$qb->select($object->fetch_fields, true, 't')
   ->from($object->table_element, 't')
   ->where($where)
   ->orderBy($sortfield, $sortorder);

// Fetch extrafields
$page->fetchExtraFields($object->table_element, $qb);

// Get total & result count
$total = $qb->count();
$qb->limit($limit+1, $offset)->execute();
$count = $qb->count();

// List fields
$list_fields = array();
// $list_fields[] = array(...);

// Print list head
$page->openList('${page_title}', 'title_generic.png', $list_fields, $search, $count, $total, $fieldstosearchall, $sortfield, $sortorder);

$odd = true;

// Print rows
foreach ($qb->result($limit) as $row)
{
	$odd = !$odd;
	$page->openRow($odd);

	// Column
	//$page->addColumn(...);

	// Extrafields
	$page->addExtraFields($row);

	$page->closeRow();
}

// close list
$page->closeList();

$page->end();
