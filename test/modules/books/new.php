<?php

// Load Dolibase
include_once 'autoload.php';

// Load Dolibase Page class
dolibase_include_once('/core/pages/create.php');

// Load Book class
dol_include_once('/books/class/book.class.php');

// Load Dolibase Dictionary Class
dolibase_include_once('/core/class/dict.php');

// Create Page using Dolibase
$page = new CreatePage("New book", '$user->rights->books->create');

// Set actions
$action = GETPOST('action', 'alpha');

// Init object
$book = new Book();

// Set fields
$page->setFields(array(
	new Field('name', 'Name', 'required'),
	new Field('type', 'Type', 'required'),
	new Field('qty', 'Qty', 'numeric|required'),
	new Field('price', 'Price', 'numeric')
));

// Create object
if ($action == 'create' && $page->checkFields() && $page->checkExtraFields($book))
{
	$ref = $book->getNextNumRef();

	if (! empty($ref))
	{
		global $user;

		$data = array(
			'ref' => $ref,
			'name' => str_escape(GETPOST('name')),
			'desc' => str_escape(GETPOST('desc')),
			'type' => GETPOST('type'),
			'qty' => GETPOST('qty'),
			'price' => empty_to_null(GETPOST('price')),
			'publication_date' => GETPOSTDATE('publication_date', true),
			'creation_date' => dolibase_now(true),
			'created_by' => $user->id
		);

		$id = $book->create($data);

		$result = $book->insertExtraFields();

		if ($id > 0 && $result >= 0) {
			// Creation OK
			dolibase_redirect('card.php?id=' . $id);
		}
	}
}

$page->begin();

// Add Sub Title
$page->addSubTitle("New book", "object_books.png@books");

// Create form
$page->openForm();

$page->openTable(array(), 'class="border" width="100%"', true, 'Use this form to add a new book<br><br>');

$page->addTextField('Name', 'name', GETPOST('name'), true, 'This is a field summary');

$page->addTextAreaField('Description', 'desc', GETPOST('desc'));

$list = Dictionary::get_active('books_dict');

$page->addRadioListField('Type', 'type', $list, GETPOST('type'), true);

$page->addNumberField('Qty', 'qty', GETPOST('qty'), true);

$page->addTextField('Price', 'price', GETPOST('price'));

$page->addDateField('Publication Date', 'publication_date', GETPOSTDATE('publication_date'));

$page->addExtraFields($book);

$page->closeTable(true);

$page->generateFormButtons();

$page->closeForm();

$page->end();
