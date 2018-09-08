<?php
/**
 * Dolibase
 * 
 * Open source framework for Dolibarr ERP/CRM
 *
 * Copyright (c) 2018 - 2019
 *
 *
 * @package     Dolibase
 * @author      AXeL
 * @copyright   Copyright (c) 2018 - 2019, AXeL-dev
 * @license     MIT
 * @link        https://github.com/AXeL-dev/dolibase
 * 
 */

dolibase_include_once('/core/class/crud_object.php');

/**
 * Logs class
 */

class Logs extends CrudObject
{
	/**
	 * @var string Name of table without prefix where object is stored
	 */
	public $table_element = 'dolibase_logs'; // e.: 'my_table'
	/**
	 * @var array Fetch fields
	 */
	public $fetch_fields = array(); // e.: array('field_1', 'field_2', 'field_3')
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
		global $db;

		$this->db = $db;

		$this->fetch_fields = array('rowid', 'module_id', 'module_name', 'object_id', 'object_element', 'action', 'datec', 'fk_user');
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
		$this->datec = $this->db->jdate($this->datec);

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
			$this->lines[$i]->datec = $this->db->jdate($this->lines[$i]->datec);
		}

		return $result;
	}

	/**
	 * Add log into database
	 *
	 * @param  object $object object
	 * @param  string $action log action
	 * @param  int    $notrigger 0=launch triggers after, 1=disable triggers
	 * @return int    <0 if KO, Id of created object if OK
	 */
	public function add($object, $action, $notrigger = 1)
	{
		global $dolibase_config, $user;

		if (isset($dolibase_config['module']['enable_logs']) && $dolibase_config['module']['enable_logs'])
		{
			$info = array(
				'module_id'      => $dolibase_config['module']['number'],
				'module_name'    => $dolibase_config['module']['name'],
				'object_id'      => $object->id,
				'object_element' => $object->element,
				'action'         => $action,
				'datec'          => dolibase_now(true),
				'fk_user'        => $user->id
			);

			return $this->create($info, $notrigger);
		}
		else
		{
			return 0;
		}
	}
}