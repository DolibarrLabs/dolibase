<?php

// Load Dolibase config file for this module (mandatory)
include_once 'config.php';
// Load Dolibase Page class
dolibase_include_once('/core/pages/log.php');
dolibase_include_once('/core/pages/document.php');
// Load object class
dol_include_once('/books/class/book.class.php');

// Create Page using Dolibase
$page = new LogPage("Book Log", '$user->rights->books->read');

// Get parameters
$id = GETPOST('id', 'int');
$ref = GETPOST('ref', 'alpha');

// Init object
$book = new Book();

if (($id > 0 || ! empty($ref)) && $book->fetch($id, $ref))
{
	// Add tabs
	$page->addTab("Card", "/books/card.php?id=".$id.'&ref='.$ref);
	$page->addTab(DocumentPage::getTabTitle($book), "/books/document.php?id=".$id.'&ref='.$ref);
	$page->addTab("Log", "/books/log.php?id=".$id.'&ref='.$ref, true);

	$page->begin();

	// Banner
	$page->showBanner($book, '/books/list.php', $book->getImage('books.png'));

	// Logs
	$page->printLogs($book->id);
}
else $page->begin();

$page->end();