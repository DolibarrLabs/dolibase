<?php

// Load Dolibase
require_once 'autoload.php';

// Load Dolibase Page class
dolibase_include_once('/core/pages/index.php');

// Load Book class
dol_include_once('/books/class/book.class.php');

// Load Dolibase QueryBuilder class
dolibase_include_once('/core/class/query_builder.php');

// Load Dolibase Dictionary class
dolibase_include_once('/core/class/dict.php');

// Create Page using Dolibase
$page = new IndexPage("Books", '$user->rights->books->read');

// Init object
$book = new Book();

$page->begin();

$page->addSubTitle("Books");

$page->openLeftSection();

$form_fields = array('Book ref. or name' => 'all');

$page->addSearchForm($form_fields, '/books/list.php', 'Search', 'summary..');

$list = Dictionary::get_all('books_dict');

$page->addStatsGraph($book->table_element, 'type', $list);

$page->addStatsGraph($book->table_element, 'type', $list, 'bars', 'Statistics - Bars');

$page->closeLeftSection();

$page->openRightSection();

$page->openTable(array(array('name' => 'Last 10 added books', 'attr' => 'colspan="3"')));

// Fetch
$qb = new QueryBuilder();
$qb->select($book->fetch_fields, true, 't')
   ->from($book->table_element, 't')
   ->orderBy('t.creation_date', 'DESC')
   ->limit(10);

// Print rows
$odd = true;

foreach ($qb->result() as $row)
{
	$odd = ! $odd;
	$page->openRow($odd);

	// Ref
	$book->_clone($row); //$book->fetch($row->rowid);
	$page->addColumn($book->getNomUrl(1), 'width="20%" class="nowrap"');

	// Creation date
	$page->addColumn(dolibase_print_date($row->creation_date, 'day'), 'align="center"');

	// Type
	$page->addColumn($list[$row->type], 'align="right" width="20%"');

	$page->closeRow();
}

$page->closeTable()->addLineBreak();

$page->addStatsGraph($book->table_element, 'type', $list, 'lines', 'Statistics - Lines');

$page->closeRightSection();

$page->end();
