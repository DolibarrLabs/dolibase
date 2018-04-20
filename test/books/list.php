<?php

// Load Dolibarr environment (mandatory)
if (false === (@include '../../main.inc.php')) {  // From htdocs directory
	require '../../../main.inc.php'; // From "custom" directory
}

// Load Dolibase config file for this module (mandatory)
dol_include_once('/books/config.php');
// Load Dolibase Page class
dolibase_include_once('/core/pages/list.php');
// Load Book class
dol_include_once('/books/class/book.class.php');

// Create Page using Dolibase
$page = new ListPage("Books List", '$user->rights->books->read');

// Get parameters
global $conf;
$sortorder = GETPOST('sortorder', 'alpha') ? GETPOST('sortorder', 'alpha') : 'DESC';
$sortfield = GETPOST('sortfield', 'alpha') ? GETPOST('sortfield', 'alpha') : 't.creation_date';
$limit = GETPOST('limit') ? GETPOST('limit', 'int') : $conf->liste_limit;
$offset = $limit * (GETPOST('page', 'int') ? GETPOST('page', 'int') : 0);

// search parameters
$sall = GETPOST('sall', 'alphanohtml');
$sref = GETPOST('sref');
$sname = GETPOST('sname');
$stype = GETPOST('stype');
$spdate = GETPOSTDATE('spdate');
$scdate = GETPOSTDATE('scdate');
$suser = GETPOST('suser');

$page->begin();

// Init object
$books = new Book();

// Adjust query
$fieldstosearchall = array('t.ref' => 'Ref.', 't.name' => 'Name');
$where = '1=1';

if ($sall) $where .= natural_search(array_keys($fieldstosearchall), $sall);
if ($sref) $where .= natural_search('t.ref', $sref);
if ($sname) $where .= natural_search('t.name', $sname);
if ($stype && $stype != -1) $where .= " AND t.type = '".$stype."'";
if ($spdate) $where .= " AND date(t.publication_date) = date('".$books->db->idate($spdate)."')";
if ($scdate) $where .= " AND date(t.creation_date) = date('".$books->db->idate($scdate)."')";
if ($suser > 0) $where .= natural_search('t.created_by', $suser);

// Fetch
$books->fetchAll($limit, $offset, $sortfield, $sortorder, '', '', $where, true);

$type_list = array('sc'   => 'Science & nature',
			  'his'  => 'History',
			  'cook' => 'Cooking',
			  'med'  => 'Medecine',
			  'psy'  => 'Psychology'
			);

// List fields
$list_fields = array();
$list_fields[] = array('name' => 't.ref', 'label' => 'Ref.', 'search_input' => $page->textInput('sref', $sref));
$list_fields[] = array('name' => 't.name', 'label' => 'Name', 'search_input' => $page->textInput('sname', $sname));
$list_fields[] = array('name' => 't.type', 'label' => 'Type', 'search_input' => $page->listInput('stype', $type_list, $stype));
$list_fields[] = array('name' => 't.qty', 'label' => 'Qty');
$list_fields[] = array('name' => 't.price', 'label' => 'Price');
$list_fields[] = array('name' => 't.publication_date', 'label' => 'Publication date', 'align' => 'center', 'search_input' => $page->dateInput('spdate', $spdate));
$list_fields[] = array('name' => 't.creation_date', 'label' => 'Creation date', 'align' => 'center', 'search_input' => $page->dateInput('scdate', $scdate));
$list_fields[] = array('name' => 't.created_by', 'label' => 'Created by', 'search_input' => $page->form->select_dolusers($suser, 'suser', 1, '', 0, '', '', 0, 0, 0, '', 0, '', 'maxwidth300'));

// List parameters
$param = '';
if ($sall) $param.= '&sall='.urlencode($sall);
if ($sref) $param.= '&sref='.urlencode($sref);
if ($sname) $param.= '&sname='.urlencode($sname);
if ($stype && $stype != -1) $param.= '&stype='.urlencode($stype);
if ($spdate) $param.= '&spdate='.urlencode($spdate);
if ($scdate) $param.= '&scdate='.urlencode($scdate);
if ($suser > 0) $param.= '&suser='.urlencode($suser);

// Print list head
$page->openList('Books List', 'title_generic.png', $list_fields, $param, $books->count, $books->total, $fieldstosearchall);

$odd = true;

// Print lines
foreach ($books->lines as $book)
{
	$odd = !$odd;
	$page->openRow($odd);

	// Ref.
	$page->addColumn('t.ref', $book->getNomUrl(1, 'Show book'));

	// Name
	$page->addColumn('t.name', $book->name);

	// Type
	$page->addColumn('t.type', $type_list[$book->type]);

	// Qty
	$page->addColumn('t.qty', $book->qty);

	// Price
	$page->addColumn('t.price', price($book->price));

	// Publication date
	$pub_date = empty($book->publication_date) ? '-' : dol_print_date($book->db->jdate($book->publication_date), "day");
	$page->addColumn('t.publication_date', $pub_date, 'align="center"');

	// Creation date
	$creation_date = empty($book->creation_date) ? '-' : dol_print_date($book->db->jdate($book->creation_date), "day");
	$page->addColumn('t.creation_date', $creation_date, 'align="center"');

	// Created by
	$userstatic = new User($book->db);
	$userstatic->fetch($book->created_by);
	$page->addColumn('t.created_by', $userstatic->getNomUrl(1));

	$page->closeRow();
}

// close list
$page->closeList();

$page->end();
