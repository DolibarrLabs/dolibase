<?php

// Load Dolibase CustomObject class
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
		$this->fetch_fields = array('rowid', 'ref', 'name', 'desc', 'type', 'qty', 'price', 'ref', 'publication_date', 'creation_date', 'created_by', 'model_pdf');

		parent::__construct();
	}

	/**
	 * Load object in memory from database
	 *
	 * @param  int     $id object Id
	 * @param  string  $ref object ref
	 * @return int     <0 if KO, >0 if OK
	 */
	public function fetch($id, $ref = '')
	{
		$result = parent::fetch($id, $ref);

		// Fix error: dol_print_date function call with deprecated value of time
		$this->creation_date = $this->db->jdate($this->creation_date);
		$this->publication_date = $this->db->jdate($this->publication_date);

		return $result;
	}

	/**
	 * Load all object entries in memory from database
	 *
	 * @param  int     $limit        fetch limit
	 * @param  int     $offset       fetch offset
	 * @param  string  $sort_field   field to sort by
	 * @param  string  $sort_order   sort order: 'DESC' or 'ASC'
	 * @param  string  $more_fields  more fields to fetch
	 * @param  string  $join         join clause
	 * @param  string  $where        where clause (without 'WHERE')
	 * @param  boolean $get_total    get total number of records or not
	 * @return int                   <0 if KO, >0 if OK
	 */
	public function fetchAll($limit = 0, $offset = 0, $sort_field = '', $sort_order = 'DESC', $more_fields = '', $join = '', $where = '', $get_total = false)
	{
		$result = parent::fetchAll($limit, $offset, $sort_field, $sort_order, $more_fields, $join, $where, $get_total);

		// Fix error: dol_print_date function call with deprecated value of time
		for ($i = 0; $i < count($this->lines); $i++) { 
			$this->lines[$i]->creation_date = $this->db->jdate($this->lines[$i]->creation_date);
			$this->lines[$i]->publication_date = $this->db->jdate($this->lines[$i]->publication_date);
		}

		return $result;
	}

	/**
	 *	Return clicable name (with picto eventually)
	 *
	 *	@param		int		$withpicto		0=No picto, 1=Include picto into link, 2=Only picto
	 *	@param		string	$title			Tooltip title
	 *	@return		string					Chain with URL
	 */
	public function getNomUrl($withpicto = 0, $title = 'Show book')
	{
		return parent::getNomUrl($withpicto, $title);
	}
}
