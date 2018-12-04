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

/**
 * QueryBuilder class
 *
 * @since 2.8.0
 */

class QueryBuilder
{
	/**
	 * @var object Query Builder Instance
	 */
	protected static $instance = null;
	/**
	 * @var DoliDb Database handler
	 */
	protected $db;
	/**
	 * @var bool|null Query result
	 */
	protected $result = null;


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		global $db;

		$this->db = $db;
		$this->select = '';
		$this->from = '';
		$this->join = ''; // why only those 3? => just to keep the right order for them when getting the query
	}

	/**
	 * Return Query Builder instance
	 *
	 */
	public static function getInstance()
	{
		self::$instance = new self();
		return self::$instance;
	}

	/**
	 * Return query
	 *
	 * @return    string    query
	 */
	public function get()
	{
		$parts = array();

		foreach (get_object_vars($this) as $key => $value)
		{
			if (! in_array($key, array('db', 'result')) && ! empty($value)) {
				$parts[$key] = $value;
			}
		}

		$query = implode(" ", $parts);

		return $query;
	}

	/**
	 * Reset Query Builder
	 *
	 */
	public function reset()
	{
		foreach (get_object_vars($this) as $key => $value)
		{
			if (! in_array($key, array('db', 'result'))) {
				unset($this->$key);
			}
		}

		$this->result = null;
	}

	/**
	 * Execute query
	 *
	 * @return    bool|null    query execution result
	 */
	public function execute()
	{
		$query = $this->get();

		if (! empty($query) && is_object($this->db)) {
			$this->result = $this->db->query($query);
		}
		else {
			$this->result = null;
		}

		return $this->result;
	}

	/**
	 * Execute query if not executed & return an array of result(s)
	 *
	 * @param     $limit   result limit
	 * @return    array    query result rows
	 */
	public function result($limit = 0)
	{
		if (is_null($this->result)) {
			$this->execute();
		}

		$rows = array();

		if (! is_null($this->result) && $this->result)
		{
			$rows_count = $this->db->num_rows($this->result);

			if ($limit > 0) {
				$rows_count = min($rows_count, $limit);
			}

			$row_num = 0;

			while($row_num < $rows_count) {
				$rows[] = $this->db->fetch_object($this->result);
				$row_num++;
			}
		}

		return $rows;
	}

	/**
	 * Execute query if not executed & return query result(s) count
	 *
	 * @return    int|null    query result rows count
	 */
	public function count()
	{
		if (is_null($this->result)) {
			$this->execute();
		}

		if (! is_null($this->result) && $this->result) {
			return $this->db->num_rows($this->result);
		}

		return null;
	}

	/**
	 * Return affected rows count of an INSERT, UPDATE or DELETE query
	 *
	 * @return    int|null    query affected rows count
	 */
	public function affected()
	{
		if (! is_null($this->result) && $this->result && (isset($this->insert) || isset($this->update) || isset($this->delete))) {
			return $this->db->affected_rows($this->result);
		}

		return null;
	}

	/**
	 * Return last id after an INSERT query
	 *
	 * @since     2.8.1
	 * @param     $table_name     table name
	 * @param     $pk_name        primary key/id field name
	 * @return    int|null        id or -1 on error
	 */
	public function lastId($table_name, $pk_name)
	{
		if (! is_null($this->result) && $this->result && isset($this->insert)) {
			return $this->db->last_insert_id(MAIN_DB_PREFIX.$table_name, $pk_name);
		}

		return null;
	}

	/**
	 * Escape field value
	 *
	 * @param     $value     field value
	 * @return    string     escaped value
	 */
	protected function escape($value)
	{
		return is_null($value) ? 'null' : "'".$value."'";
	}

	/**
	 * Add SELECT statement to query
	 *
	 * @param     $select_options     select options string or array
	 * @param     $distinct           use DISTINCT or not
	 * @param     $table_alias        table alias (works only when $select_options is an array)
	 * @return    $this
	 */
	public function select($select_options = '*', $distinct = false, $table_alias = '')
	{
		$this->select = "SELECT ";

		if ($distinct) {
			$this->select.= "DISTINCT ";
		}

		if (is_array($select_options))
		{
			$alias = (! empty($table_alias) ? $table_alias.'.' : '');

			foreach ($select_options as $field) {
				$this->select.= $alias."`" . $field . "`,";
			}
			$this->select = substr($this->select, 0, -1); // Remove the last ','
		}
		else
		{
			$this->select.= $select_options;
		}

		return $this;
	}

	/**
	 * Add more options to SELECT statement (multiple calls allowed)
	 *
	 * @since     2.8.2
	 * @param     $select_options     select options string or array
	 * @param     $table_alias        table alias (works only when $select_options is an array)
	 * @return    $this
	 */
	public function addSelect($select_options, $table_alias = '')
	{
		if (isset($this->select) && ! empty($this->select)) {
			$this->select.= (is_string($select_options) && (empty($select_options) || $select_options[0] == ",") ? "" : ",");
		}
		else {
			$this->select = "SELECT ";
		}

		if (is_array($select_options))
		{
			$alias = (! empty($table_alias) ? $table_alias.'.' : '');

			foreach ($select_options as $field) {
				$this->select.= $alias."`" . $field . "`,";
			}
			$this->select = substr($this->select, 0, -1); // Remove the last ','
		}
		else
		{
			$this->select.= $select_options;
		}

		return $this;
	}

	/**
	 * Add FROM clause to query
	 *
	 * @param     $table_name     table name string or array
	 * @param     $table_alias    table alias (works only when $table_name is a string)
	 * @return    $this
	 */
	public function from($table_name, $table_alias = '')
	{
		$this->from = "FROM ";

		if (is_array($table_name))
		{
			foreach ($table_name as $table) {
				$this->from.= MAIN_DB_PREFIX.$table.", ";
			}
			$this->from = substr($this->from, 0, -2); // Remove the last ', '
		}
		else
		{
			$this->from.= MAIN_DB_PREFIX.$table_name;

			if (! empty($table_alias)) {
				$this->from.= " AS ".$table_alias;
			}
		}

		return $this;
	}

	/**
	 * Add WHERE clause to query (multiple calls allowed)
	 *
	 * @param     $where_options     where options string or array
	 * @return    $this
	 */
	public function where($where_options)
	{
		if (isset($this->where)) {
			$this->where.= (is_string($where_options) && (empty($where_options) || preg_match('/^\s*(AND|OR)/i', $where_options)) ? "" : " AND ");
		}
		else {
			$this->where = "WHERE ";
		}

		if (is_array($where_options))
		{
			$count = 0;
			foreach ($where_options as $field => $value)
			{
				if ($count > 0) {
					$this->where.= " AND ";
				}
				$this->where.= $field . " = '" . $value . "'";
				$count++;
			}
		}
		else
		{
			$this->where.= $where_options;
		}

		return $this;
	}

	/**
	 * Add OR to WHERE clause (multiple calls allowed)
	 *
	 * @param     $where_options     where options string or array
	 * @return    $this
	 */
	public function orWhere($where_options)
	{
		if (isset($this->orWhere)) {
			$this->orWhere.= (is_string($where_options) && (empty($where_options) || preg_match('/^\s*(AND|OR)/i', $where_options)) ? "" : " OR ");
		}
		else {
			$this->orWhere = "OR ";
		}

		if (is_array($where_options))
		{
			$count = 0;
			foreach ($where_options as $field => $value)
			{
				if ($count > 0) {
					$this->orWhere.= " OR ";
				}
				$this->orWhere.= $field . " = '" . $value . "'";
				$count++;
			}
		}
		else
		{
			$this->orWhere.= $where_options;
		}

		return $this;
	}

	/**
	 * Add GROUP BY clause to query
	 *
	 * @param     $group_options     group options string
	 * @return    $this
	 */
	public function groupBy($group_options)
	{
		$this->groupBy = "GROUP BY ".$group_options;
		return $this;
	}

	/**
	 * Add ORDER BY clause to query
	 *
	 * @param     $order_options     order options string
	 * @param     $order             order 'ASC' or 'DESC', default: 'ASC'
	 * @return    $this
	 */
	public function orderBy($order_options, $order = 'ASC')
	{
		$this->orderBy = "ORDER BY ".$order_options." ".$order;
		return $this;
	}

	/**
	 * Add LIMIT clause to query
	 *
	 * @param     $limit     limit integer
	 * @param     $offset    offset integer
	 * @return    $this
	 */
	public function limit($limit, $offset = 0)
	{
		if ($offset > 0) {
			$this->limit = "LIMIT ".$offset.",".$limit;
		}
		else {
			$this->limit = "LIMIT ".$limit;
		}

		return $this;
	}

	/**
	 * Add JOIN clause to query (multiple calls allowed)
	 *
	 * @param     $table_name     table name
	 * @param     $join_options   join options string
	 * @param     $join_type      join type 'left', 'right', 'inner', 'outer'
	 * @return    $this
	 */
	public function join($table_name, $join_options, $join_type = '')
	{
		if (isset($this->join) && ! empty($this->join)) {
			$this->join.= " ".strtoupper($join_type);
		}
		else {
			$this->join = strtoupper($join_type);
		}

		if (! empty($join_type)) {
			$this->join.= " ";
		}

		$this->join.= "JOIN ".MAIN_DB_PREFIX.$table_name." ON ".$join_options;

		return $this;
	}

	/**
	 * Add INSERT statement to query
	 *
	 * @param     $table_name     table name
	 * @param     $data           data array ['key' => 'value']
	 * @return    $this
	 */
	public function insert($table_name, $data)
	{
		$this->insert = "INSERT INTO ".MAIN_DB_PREFIX.$table_name." (";

		foreach ($data as $key => $value) {
			$this->insert.= "`" . $key . "`,";
		}
		$this->insert = substr($this->insert, 0, -1); // Remove the last ','

		$this->insert.= ") VALUES (";

		foreach ($data as $key => $value) {
			$this->insert.= $this->escape($value) . ",";
		}
		$this->insert = substr($this->insert, 0, -1); // Remove the last ','

		$this->insert.= ")";

		return $this;
	}

	/**
	 * Add UPDATE statement to query
	 *
	 * @param     $table_name     table name
	 * @param     $data           data array ['key' => 'value']
	 * @return    $this
	 */
	public function update($table_name, $data)
	{
		$this->update = "UPDATE ".MAIN_DB_PREFIX.$table_name." SET ";

		foreach ($data as $key => $value) {
			$this->update.= "`" . $key . "` = " . $this->escape($value) . ",";
		}
		$this->update = substr($this->update, 0, -1); // Remove the last ','

		return $this;
	}

	/**
	 * Add DELETE statement to query
	 *
	 * @param     $table_name     table name
	 * @return    $this
	 */
	public function delete($table_name)
	{
		$this->delete = "DELETE FROM ".MAIN_DB_PREFIX.$table_name;
		return $this;
	}

	/**
	 * Add TRUNCATE statement to query
	 *
	 * @param     $table_name     table name
	 * @return    $this
	 */
	public function truncate($table_name)
	{
		$this->truncate = "TRUNCATE ".MAIN_DB_PREFIX.$table_name;
		return $this;
	}
}
