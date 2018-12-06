<?php

// Load Dolibase
include_once 'autoload.php';

// Load Dolibase Page class
dolibase_include_once('/core/pages/list.php');

// Load Book class
dol_include_once('/books/class/book.class.php');

// Load Dolibase QueryBuilder class
dolibase_include_once('/core/class/query_builder.php');

// Load Dolibase Dictionary class
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

// Init objects
$book = new Book();
$userstatic = new User($book->db);

// Adjust query
$fieldstosearchall = array('t.ref' => 'Ref.', 't.name' => 'Name');
$where = '1=1';

if ($search['all']) $where .= natural_search(array_keys($fieldstosearchall), $search['all']);
if ($search['ref']) $where .= natural_search('t.ref', $search['ref']);
if ($search['name']) $where .= natural_search('t.name', $search['name']);
if ($search['type'] && $search['type'] != -1) $where .= " AND t.type = '".$search['type']."'";
if ($search['pd']) $where .= " AND date(t.publication_date) = date('".$book->db->idate($search['pd'])."')";
if ($search['cd']) $where .= " AND date(t.creation_date) = date('".$book->db->idate($search['cd'])."')";
if ($search['user'] > 0) $where .= natural_search('t.created_by', $search['user']);

// Fetch
$qb = new QueryBuilder();
$qb->select($book->fetch_fields, true, 't')
   ->from($book->table_element, 't')
   ->where($where)
   ->orderBy($sortfield, $sortorder);

// Fetch extrafields
$page->fetchExtraFields($book->table_element, $qb);

// Get total & result count
$total = $qb->count();
$qb->limit($limit+1, $offset)->execute();
$count = $qb->count();

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
$page->openList('Books List', 'title_generic.png', $list_fields, $search, $count, $total, $fieldstosearchall, $sortfield, $sortorder);

$odd = true;

// Print rows
foreach ($qb->result($limit) as $row)
{
	$odd = !$odd;
	$page->openRow($odd);

	// Ref.
	$book->clone($row); //$book->fetch($row->rowid);
	$page->addColumn('t.ref', $book->getNomUrl(1));

	// Name
	$page->addColumn('t.name', $row->name);

	// Type
	$page->addColumn('t.type', $full_type_list[$row->type]);

	// Qty
	$page->addColumn('t.qty', $row->qty);

	// Price
	$page->addColumn('t.price', price($row->price));

	// Publication date
	$pub_date = empty($row->publication_date) ? '-' : dolibase_print_date($row->publication_date, "day");
	$page->addColumn('t.publication_date', $pub_date, 'align="center"');

	// Creation date
	$creation_date = empty($row->creation_date) ? '-' : dolibase_print_date($row->creation_date, "day");
	$page->addColumn('t.creation_date', $creation_date, 'align="center"');

	// Created by
	$userstatic->fetch($row->created_by);
	$page->addColumn('t.created_by', $userstatic->getNomUrl(1), 'align="center"');

	// Extrafields
	$page->addExtraFields($row);

	$page->closeRow();
}

// close list
$page->closeList();

$page->end();
