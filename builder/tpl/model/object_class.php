<?php

// Load Dolibase CustomObject class
dolibase_include_once('/core/class/custom_object.php');

class ${object_classname} extends CustomObject
{
	/**
	 * @var string Id to identify managed object
	 */
	public $element = '${object_element}';
	/**
	 * @var string Name of table without prefix where object is stored
	 */
	public $table_element = '${object_table}';
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
		$this->fetch_fields = array(${fetch_fields});

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

		${fetch_function_date_fields}

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
	 * @param  boolean $table_alias  Alias to use for table name, leave it empty if you won't
	 * @return int                   <0 if KO, >0 if OK
	 */
	public function fetchAll($limit = 0, $offset = 0, $sort_field = '', $sort_order = 'DESC', $more_fields = '', $join = '', $where = '', $get_total = false, $table_alias = 't')
	{
		$result = parent::fetchAll($limit, $offset, $sort_field, $sort_order, $more_fields, $join, $where, $get_total, $table_alias);

		${fetchAll_function_date_fields}

		return $result;
	}

	/**
	 *	Return clicable name (with picto eventually)
	 *
	 *	@param		int		$withpicto		0=No picto, 1=Include picto into link, 2=Only picto
	 *	@param		string	$title			Tooltip title
	 *	@return		string					Chain with URL
	 */
	public function getNomUrl($withpicto = 0, $title = '${tooltip_title}')
	{
		return parent::getNomUrl($withpicto, $title);
	}
}
