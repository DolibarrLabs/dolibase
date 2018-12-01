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
 */

class QueryBuilder
{
	/**
	 * @var object Quey Builder Instance
	 */
	protected static $instance = null;


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
	 * @return    query string
	 */
	public function get()
	{
		$parts = (array)$this;
		$query = implode(" ", $parts);

		return $query;
	}

	/**
	 * Execute query
	 *
	 * @return    query result
	 */
	public function execute()
	{
		global $db;

		$query = $this->get();

		if (! empty($query) && is_object($db)) {
			return $db->query($query);
		}

		return null;
	}

	/**
	 * Escape field value
	 *
	 * @param     $value     field value
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
	 * @param     $table_alias        table alias
	 * @return    $this
	 */
	public function select($select_options = '*', $distinct = false, $table_alias = '')
	{
		$this->select = "SELECT ";

		if ($distinct) {
			$this->select.= "DISTINCT ";
		}

		$alias = (! empty($table_alias) ? $table_alias.'.' : '');

		if (is_array($select_options)) {
			foreach ($this->select_options as $field) {
				$this->select.= $alias."`" . $field . "`,";
			}
			$this->select = substr($this->select, 0, -1); // Remove the last ','
		}
		else {
			$this->select.= $select_options;
		}

		return $this;
	}

	/**
	 * Add FROM clause to query
	 *
	 * @param     $table_name     table name
	 * @param     $table_alias    table alias
	 * @return    $this
	 */
	public function from($table_name, $table_alias = '')
	{
		$this->from = "FROM ".$table_name;

		if (! empty($table_alias)) {
			$this->from.= " AS ".$table_alias;
		}

		return $this;
	}

	/**
	 * Add WHERE clause to query
	 *
	 * @param     $where_options     where options string or array
	 * @return    $this
	 */
	public function where($where_options)
	{
		$this->where = "WHERE ";

		if (is_array($where_options)) {
			$count = 0;
			foreach ($where_options as $field => $value) {
				$this->where.= ($count > 0 ? ' AND ' : '') . $field . " = '" . $value . "'";
				$count++;
			}
		}
		else {
			$this->where.= $where_options;
		}

		return $this;
	}

	/**
	 * Add OR to WHERE clause
	 *
	 * @param     $where_options     where options string or array
	 * @return    $this
	 */
	public function orWhere($where_options)
	{
		$this->orWhere = " OR ";

		if (is_array($where_options)) {
			$count = 0;
			foreach ($where_options as $field => $value) {
				$this->orWhere.= ($count > 0 ? ' OR ' : '') . $field . " = '" . $value . "'";
				$count++;
			}
		}
		else {
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
	 * Add JOIN clause to query
	 *
	 * @param     $table_name     table name
	 * @param     $join_options   join options string
	 * @param     $join_type      join type 'left', 'right', 'inner', 'outer'
	 * @return    $this
	 */
	public function join($table_name, $join_options, $join_type = '')
	{
		$this->join = strtoupper($join_type);

		if (! empty($join_type)) {
			$this->join.= " ";
		}

		$this->join.= "JOIN ".$table_name." ON ".$join_options;

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
		$this->insert = "INSERT INTO ".$table_name." (";

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
		$this->update = "UPDATE ".$table_name." SET ";

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
		$this->delete = "DELETE FROM ".$table_name;
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
		$this->truncate = "TRUNCATE ".$table_name;
		return $this;
	}
}
