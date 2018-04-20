<?php

// Load Dolibarr environment (mandatory)
if (false === (@include '../../main.inc.php')) {  // From htdocs directory
	require '../../../main.inc.php'; // From "custom" directory
}

// Load Dolibase config file for this module (mandatory)
dol_include_once('/books/config.php');
// Load Dolibase Page class
dolibase_include_once('/core/pages/index.php');
// Load Book class
dol_include_once('/books/class/book.class.php');

// Create Page using Dolibase
$page = new IndexPage("Books", '$user->rights->books->read');

// Init object
$books = new Book();

$page->begin();

$page->addSubTitle("Books");

$page->openLeftSection();

$form_fields = array('Book ref. or name' => 'sall');

$page->addSearchForm($form_fields, '/books/list.php', 'Search', 'summary..');

$list = array('sc'   => 'Science & nature',
			  'his'  => 'History',
			  'cook' => 'Cooking',
			  'med'  => 'Medecine',
			  'psy'  => 'Psychology'
			);

$page->addStatsGraph($books->table_element, 'type', $list);

$page->addStatsGraph($books->table_element, 'type', $list, 'barline', 'Statistics - Barline');

$page->closeLeftSection();

$page->openRightSection();

$page->openTable(array(array('name' => 'Last 10 added books', 'attr' => 'colspan="3"')));

if ($books->fetchAll(10, 0, 't.creation_date'))
{
	$odd = true;

	foreach ($books->lines as $book)
	{
		$odd = ! $odd;
		$page->openRow($odd);

		// Ref
		$page->addColumn($book->getNomUrl(1, 'Show book'), 'width="20%" class="nowrap"');

		// Creation date
		$page->addColumn(dol_print_date($book->db->jdate($book->creation_date), 'day'), 'align="center"');

		// Type
		$page->addColumn($list[$book->type], 'align="right" width="20%"');

		$page->closeRow();
	}
}

$page->closeTable();

$page->closeRightSection();

$page->end();
