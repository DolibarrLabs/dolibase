<?php

// Load Dolibase config file for this module (mandatory)
include_once 'config.php';
// Load Dolibase Page class
dolibase_include_once('/core/pages/card.php');
dolibase_include_once('/core/pages/document.php');
// Load Book class
dol_include_once('/books/class/book.class.php');
// Load Dolibase Dictionary Class
dolibase_include_once('/core/class/dict.php');

// Create Page using Dolibase
$page = new CardPage("Book Card", '$user->rights->books->read', '$user->rights->books->modify', '$user->rights->books->delete', true, true);

// Set fields
$page->fields[] = new Field('name', 'Name', 'required');
$page->fields[] = new Field('type', 'Type', 'required');
$page->fields[] = new Field('qty', 'Qty', 'numeric|required');
$page->fields[] = new Field('price', 'Price', 'numeric');

// Get parameters
$id = GETPOST('id', 'int');
$ref = GETPOST('ref', 'alpha');
$action = GETPOST('action', 'alpha');
$confirm = GETPOST('confirm', 'alpha');
$optioncss = GETPOST('optioncss', 'alpha');
$model = GETPOST('model', 'alpha');

// Init object
$book = new Book();

if (($id > 0 || ! empty($ref)) && $book->fetch($id, $ref))
{
	// Set actions ---

	// Edit
	if ($page->canEdit() && preg_match('/set_(.*)/', $action, $field) && $page->checkField($field[1]))
	{
		if (preg_match('/date_(.*)/', $field[1], $datefield)) {
			$val  = GETPOSTDATE($datefield[1], true);
			$data = array($datefield[1] => $val);
		}
		else {
			$val  = GETPOST($field[1]);
			$val  = empty($val) ? empty_to_null($val) : str_escape($val);
			$data = array($field[1] => $val);
		}

		$book->update($data);
	}

	// Edit extrafields
	if ($page->canEdit() && $action == 'update_extras')
	{
		$result = $page->updateExtraFields($book);
		if ($result) $action = 'edit_extras';
	}

	// Delete
	if ($page->canDelete())
	{
		if ($action == 'delete')
		{
	        $page->askForConfirmation($_SERVER["PHP_SELF"] . '?id=' . $book->id, 'Delete', 'Confirm Delete', 'confirm_delete', $book->ref);
	    }
		else if ($action == 'confirm_delete' && $confirm == 'yes')
		{
			$result = $book->delete();
			if ($result > 0) {
				header('Location: list.php');
				exit();
	        }
		}
	}

	// Build doc
	if ($action == 'builddoc')
	{
		$list = Dictionary::get_all('books_dict');

		$book->lines = array(
			array('name' => 'Ref', 'value' => $book->ref),
			array('name' => 'Name', 'value' => $book->name),
			array('name' => 'Description', 'value' => $book->desc),
			array('name' => 'Type', 'value' => $list[$book->type]),
			array('name' => 'Publication Date', 'value' => dol_print_date($book->publication_date, 'daytext')),
			array('name' => 'Qty', 'value' => $book->qty),
			array('name' => 'Price', 'value' => price_with_currency($book->price))
		);

		$book->documentTitle = 'Book';
		$book->generateDocument($model);
	}

	// Remove file in doc form
	if ($action == 'remove_file')
	{
		$book->deleteDocument();
	}

	// Send by mail
	if ($action == 'send')
	{
		$object = $book;
		$object->fk_thirdparty = 1; // only to fix 'ErrorFailedToReadObject' error message
		include DOL_DOCUMENT_ROOT.'/core/actions_sendmails.inc.php';
	}

	// --- End actions

	// Add tabs
	$page->addTab("Card", "/books/card.php?id=".$id.'&ref='.$ref, true);
	$page->addTab(DocumentPage::getTabTitle($book), "/books/document.php?id=".$id.'&ref='.$ref);
	$page->addTab("Log", "/books/log.php?id=".$id.'&ref='.$ref);

	$page->begin();

	$page->openTable(array(), 'class="border" width="100%"');

	// Ref.
	$page->showRefField('Ref.', $book, '/books/list.php');

	// Name
	if ($action != 'edit_name' || ! $page->canEdit()) {
		$page->showField('Name', $book->name, true, $_SERVER["PHP_SELF"] . '?action=edit_name&id=' . $book->id);
	}
	else {
		$page->editTextField('Name', 'name', $book->name);
	}

	// Description
	if ($action != 'edit_desc' || ! $page->canEdit()) {
		$page->showField('Description', $book->desc, true, $_SERVER["PHP_SELF"] . '?action=edit_desc&id=' . $book->id);
	}
	else {
		$page->editTextAreaField('Description', 'desc', $book->desc);
	}

	// Type
	if ($action != 'edit_type' || ! $page->canEdit()) {
		$list = Dictionary::get_all('books_dict');
		$page->showField('Type', $list[$book->type], true, $_SERVER["PHP_SELF"] . '?action=edit_type&id=' . $book->id);
	}
	else {
		$list = Dictionary::get_active('books_dict');
		$page->editRadioListField('Type', 'type', $list, $book->type);
	}

	// Qty
	if ($action != 'edit_qty' || ! $page->canEdit()) {
		$page->showField('Qty', $book->qty, true, $_SERVER["PHP_SELF"] . '?action=edit_qty&id=' . $book->id);
	}
	else {
		$page->editNumberField('Qty', 'qty', $book->qty);
	}

	// Price
	if ($action != 'edit_price' || ! $page->canEdit()) {
		$page->showField('Price', price_with_currency($book->price), true, $_SERVER["PHP_SELF"] . '?action=edit_price&id=' . $book->id);
	}
	else {
		$page->editTextField('Price', 'price', $book->price);
	}

	// Pub date
	if ($action != 'edit_publication_date' || ! $page->canEdit()) {
		$page->showField('Publication Date', dol_print_date($book->publication_date, 'daytext'), true, $_SERVER["PHP_SELF"] . '?action=edit_publication_date&id=' . $book->id);
	}
	else {
		$page->editDateField('Publication Date', 'publication_date', $book->publication_date);
	}

	// Creation date
	$page->showField('Creation Date', dol_print_date($book->creation_date, 'daytext'));

	// Created by
	$userstatic = new User($book->db);
    $userstatic->fetch($book->created_by);
	$page->showField('Created by', $userstatic->getNomUrl(1));

	// Extra fields
	$page->showExtraFields($book);

	$page->closeTable();

	// Action buttons
	if ($optioncss != 'print' && $action != 'presend')
	{
		// Send by mail
		$page->addButton('SendByMail', $_SERVER["PHP_SELF"] . '?id=' . $book->id . '&action=presend&mode=init');

		// Save as
		$page->addSaveAsButton();

		// Print
		$page->addButton('Print', $_SERVER["PHP_SELF"] . '?id=' . $book->id . '&optioncss=print', '_blank');

		// Delete
		if ($page->canDelete()) {
			$page->addButton('Delete', $_SERVER["PHP_SELF"] . '?id=' . $book->id . '&action=delete', '', 'butActionDelete', true);
		}
	}
}
else $page->begin();

$page->end($book);
