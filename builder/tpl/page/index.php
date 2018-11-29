<?php

// Load Dolibase
include_once 'autoload.php';

// Load Dolibase Page class
dolibase_include_once('/core/pages/index.php');

// Load Object class
${object_class_include}

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

if ($object->fetchAll(10, 0, 't.creation_date'))
{
	$odd = true;

	foreach ($object->lines as $obj)
	{
		$odd = ! $odd;
		$page->openRow($odd);

		// Column
		//$page->addColumn(...);

		$page->closeRow();
	}
}

$page->closeTable()->addLineBreak();

$page->closeRightSection();

$page->end();
