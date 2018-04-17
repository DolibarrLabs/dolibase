<?php

// Load Dolibase Page class
dolibase_include_once('/core/class/custom_object.php');

class Book extends CustomObject
{
	/**
	 * @var string Id to identify managed object
	 */
	public $element = 'book';
	/**
	 * @var string Name of table without prefix where object is stored
	 */
	public $table_element = 'books';
	/**
	 * @var string Primary key name (id field)
	 */
	public $pk_name = 'rowid';

	/**
	 * Constructor
	 * 
	 */
	public function __construct()
	{
		$this->fetch_fields = array('id'               => 'rowid',
									'ref'              => 'ref',
									'name'             => 'name',
									'description'      => 'desc',
									'type'             => 'type',
									'qty'              => 'qty',
									'price'            => 'price',
									'ref'              => 'ref',
									'publication_date' => 'publication_date',
									'creation_date'    => 'creation_date',
									'created_by'       => 'created_by'
								);

		parent::__construct();
	}
}
