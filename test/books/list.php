<?php

// Load Dolibase
include_once 'autoload.php';
// Load Dolibase Page class
dolibase_include_once('/core/pages/list.php');
// Load Book class
dol_include_once('/books/class/book.class.php');
// Load Dolibase Dictionary Class
dolibase_include_once('/core/class/dict.php');

// Create Page using Dolibase
$page = new ListPage("Books List", '$user->rights->books->read');

// Get parameters
global $conf;
$sortorder = GETPOST('sortorder', 'alpha') ? GETPOST('sortorder', 'alpha') : 'DESC';
$sortfield = GETPOST('sortfield', 'alpha') ? GETPOST('sortfield', 'alpha') : 't.creation_date';
$limit = GETPOST('limit') ? GETPOST('limit', 'int') : $conf->liste_limit;
$offset = $limit * (GETPOST('page', 'int') ? GETPOST('page', 'int') : 0);

// search parameters
$search = array();
$search['all'] = GETPOST('all', 'alphanohtml');
$search['ref'] = GETPOST('ref');
$search['name'] = GETPOST('name');
$search['type'] = GETPOST('type');
$search['pd'] = GETPOSTDATE('pd'); // publication date
$search['cd'] = GETPOSTDATE('cd'); // creation date
$search['user'] = GETPOST('user');

$page->begin();

// Init object
$books = new Book();

// Adjust query
$fieldstosearchall = array('t.ref' => 'Ref.', 't.name' => 'Name');
$where = '1=1';

if ($search['all']) $where .= natural_search(array_keys($fieldstosearchall), $search['all']);
if ($search['ref']) $where .= natural_search('t.ref', $search['ref']);
if ($search['name']) $where .= natural_search('t.name', $search['name']);
if ($search['type'] && $search['type'] != -1) $where .= " AND t.type = '".$search['type']."'";
if ($search['pd']) $where .= " AND date(t.publication_date) = date('".$books->db->idate($search['pd'])."')";
if ($search['cd']) $where .= " AND date(t.creation_date) = date('".$books->db->idate($search['cd'])."')";
if ($search['user'] > 0) $where .= natural_search('t.created_by', $search['user']);

// Fetch extrafields
$more_fields = '';
$join = '';
$page->fetchExtraFields($books->table_element, $more_fields, $join, $where);

// Fetch
$books->fetchAll($limit, $offset, $sortfield, $sortorder, $more_fields, $join, $where, true);

$type_list = Dictionary::get_active('books_dict');
$full_type_list = Dictionary::get_all('books_dict');

// List fields
$list_fields = array();
$list_fields[] = array('name' => 't.ref', 'label' => 'Ref.', 'search_input' => $page->form->textInput('ref', $search['ref']));
$list_fields[] = array('name' => 't.name', 'label' => 'Name', 'search_input' => $page->form->textInput('name', $search['name']));
$list_fields[] = array('name' => 't.type', 'label' => 'Type', 'search_input' => $page->form->listInput('type', $type_list, $search['type'], 1));
$list_fields[] = array('name' => 't.qty', 'label' => 'Qty');
$list_fields[] = array('name' => 't.price', 'label' => 'Price');
$list_fields[] = array('name' => 't.publication_date', 'label' => 'Publication date', 'align' => 'center', 'search_input' => $page->form->dateInput('pd', $search['pd'], 0));
$list_fields[] = array('name' => 't.creation_date', 'label' => 'Creation date', 'align' => 'center', 'search_input' => $page->form->dateInput('cd', $search['cd'], 0));
$list_fields[] = array('name' => 't.created_by', 'label' => 'Created by', 'align' => 'center', 'search_input' => $page->form->select_dolusers($search['user'], 'user', 1, '', 0, '', '', 0, 0, 0, '', 0, '', 'maxwidth300'));

// Print list head
$page->openList('Books List', 'title_generic.png', $list_fields, $search, $books->count, $books->total, $fieldstosearchall, $sortfield, $sortorder);

$odd = true;

// Print lines
foreach ($books->lines as $book)
{
	$odd = !$odd;
	$page->openRow($odd);

	// Ref.
	$page->addColumn('t.ref', $book->getNomUrl(1));

	// Name
	$page->addColumn('t.name', $book->name);

	// Type
	$page->addColumn('t.type', $full_type_list[$book->type]);

	// Qty
	$page->addColumn('t.qty', $book->qty);

	// Price
	$page->addColumn('t.price', price($book->price));

	// Publication date
	$pub_date = empty($book->publication_date) ? '-' : dol_print_date($book->publication_date, "day");
	$page->addColumn('t.publication_date', $pub_date, 'align="center"');

	// Creation date
	$creation_date = empty($book->creation_date) ? '-' : dol_print_date($book->creation_date, "day");
	$page->addColumn('t.creation_date', $creation_date, 'align="center"');

	// Created by
	$userstatic = new User($book->db);
	$userstatic->fetch($book->created_by);
	$page->addColumn('t.created_by', $userstatic->getNomUrl(1), 'align="center"');

	// Extrafields
	$page->addExtraFields($book);

	$page->closeRow();
}

// close list
$page->closeList();

$page->end();
