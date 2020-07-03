<?php

// Load Dolibase
dol_include_once('/books/autoload.php');

// Load Dolibase Widget class
dolibase_include_once('/core/class/widget.php');

// Load Book class
dol_include_once('/books/class/book.class.php');

// Load Dolibase QueryBuilder class
dolibase_include_once('/core/class/query_builder.php');

/**
 * Class to manage the box
 *
 * Warning: for the box to be detected correctly by dolibarr,
 * the filename should be the lowercase classname
 */
class BooksWidget extends Widget
{
	/**
	 * @var Widget Label
	 */
	public $boxlabel = "Books";
	/**
	 * @var Widget Picture
	 */
	public $boximg = "books.png";


	/**
	 * Load data into info_box_contents array to show array later. Called by Dolibarr before displaying the box.
	 *
	 * @param int $max Maximum number of records to load
	 * @return void
	 */
	public function loadBox($max = 5)
	{
		$this->setTitle("Last 5 added books");

		// Init objects
		$book = new Book();
		$userstatic = new User($book->db);

		// Fetch
		$qb = new QueryBuilder();
		$qb->select($book->fetch_fields, true, 't')
		   ->from($book->table_element, 't')
		   ->orderBy('t.creation_date', 'DESC')
		   ->limit(5);

		// Print rows
		foreach ($qb->result() as $row)
		{
			// Ref
			$book->_clone($row); //$book->fetch($row->rowid);
			$this->addContent($book->getNomUrl(1), 'align="left"');

			// Creation date
			$this->addContent(dolibase_print_date($row->creation_date, 'day'));

			// Created by
			$userstatic->fetch($row->created_by);
			$this->addContent($userstatic->getNomUrl(1), 'align="right"');

			$this->newLine();
		}
	}
}
