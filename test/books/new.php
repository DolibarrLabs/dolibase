<?php

// Load Dolibarr environment (mandatory)
if (false === (@include '../../main.inc.php')) {  // From htdocs directory
	require '../../../main.inc.php'; // From "custom" directory
}

// Load Dolibase config file for this module (mandatory)
dol_include_once('/books/config.php');
// Load Dolibase Page class
dolibase_include_once('/core/pages/create.php');
// Load Book class
dol_include_once('/books/class/book.class.php');

// Create Page using Dolibase
$page = new CreatePage("New book", '$user->rights->books->create');

// Set fields
$page->fields[] = new Field('book_ref', 'Ref', 'required');
$page->fields[] = new Field('book_name', 'Name', 'required');
$page->fields[] = new Field('book_type', 'Type', 'required');
$page->fields[] = new Field('book_qty', 'Qty', 'numeric|required');
$page->fields[] = new Field('book_price', 'Price', 'numeric');

// Set actions
$action = GETPOST('action', 'alpha');

if ($action == 'create' && $page->checkFields())
{
	global $db;

	$book = new Book();

	$data = array('ref' => GETPOST('book_ref'),
				  'name' => GETPOST('book_name'),
				  'desc' => GETPOST('book_desc'),
				  'type' => GETPOST('book_type'),
				  'qty' => GETPOST('book_qty'),
				  'price' => empty_to_null(GETPOST('book_price')),
				  'publication_date' => GETPOSTDATE('book_pub_date', true),
				  'creation_date' => dolibase_now(true),
				  'created_by' => $user->id
				);

	$id = $book->create($data);

	if ($id > 0) {
        // Creation OK
        header('Location: card.php?id=' . $id);
        exit();
    }
    else {
        // Creation KO
        setEventMessage($book->error, 'errors');
    }
}

$page->begin();

// Add Sub Title
$page->AddSubTitle("New book", "object_books.png@books");

// Create form
$page->openForm();

$page->openTable(array(), 'class="border" width="100%"', true, 'Use this form to add a new book<br><br>');

$page->addTextField('Ref.', 'book_ref', GETPOST('book_ref'), true);

$page->addTextField('Name', 'book_name', GETPOST('book_name'), true);

$page->addTextAreaField('Description', 'book_desc', GETPOST('book_desc'));

$page->addRadioListField('Type', 'book_type', array('sc' => 'Science & nature', 'his' => 'History', 'cook' => 'Cooking', 'med' => 'Medecine', 'psy' => 'Psychology'), GETPOST('book_type'), true);

$page->addNumberField('Qty', 'book_qty', GETPOST('book_qty'), true);

$page->addTextField('Price', 'book_price', GETPOST('book_price'));

$page->addDateField('Publication Date', 'book_pub_date', GETPOSTDATE('book_pub_date'));

$page->closeTable(true);

$page->generateFormButtons();

$page->closeForm();

$page->end();
