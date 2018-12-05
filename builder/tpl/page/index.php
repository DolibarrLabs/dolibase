<?php

// Load Dolibase
include_once 'autoload.php';

// Load Dolibase Page class
dolibase_include_once('/core/pages/index.php');

// Load Object class
${object_class_include}

// Load Dolibase QueryBuilder class
dolibase_include_once('/core/class/query_builder.php');

// Create Page using Dolibase
$page = new IndexPage('${page_title}', '${access_perms}');

// Init object
${object_init}

$page->begin();

$page->addSubTitle('${page_title}');

// Left section
$page->openLeftSection();

$form_fields = array();

$page->addSearchForm($form_fields, '/${module_folder}/list.php', 'Search');

//$page->addStatsGraph(...);

$page->closeLeftSection();

// Right Section
$page->openRightSection();

$page->openTable(array(
	array('name' => 'Last 10 rows', 'attr' => 'colspan="3"')
));

// Fetch
$qb = new QueryBuilder();
$qb->select($object->fetch_fields, true, 't')
   ->from($object->table_element, 't')
   //->orderBy('t.creation_date', 'DESC')
   ->limit(10);

// Print rows
$odd = true;

foreach ($qb->result() as $row)
{
	$odd = ! $odd;
	$page->openRow($odd);

	// Column
	//$page->addColumn(...);

	$page->closeRow();
}

$page->closeTable()->addLineBreak();

$page->closeRightSection();

$page->end();
