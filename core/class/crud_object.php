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

require_once DOL_DOCUMENT_ROOT . '/core/class/commonobject.class.php';

/**
 * CrudObject class (Create/Read/Update/Delete)
 */

class CrudObject extends CommonObject
{
	/**
	 * @var string Id to identify managed object
	 */
	public $element = ''; // e.: 'my_object'
	/**
	 * @var string Name of table without prefix where object is stored
	 */
	public $table_element = ''; // e.: 'my_table'
	/**
	 * @var array Fetch fields
	 */
	public $fetch_fields = array(); // e.: array('field_1', 'field_2', 'field_3')
	/**
	 * @var array Date fields
	 */
	public $date_fields = array(); // e.: array('creation_date')
	/**
	 * @var string Primary key name (id field)
	 */
	public $pk_name = 'rowid';
	/**
	 * @var string Ref. field name
	 */
	public $ref_field_name = 'ref';
	/**
	 * @var array Object lines (used in fetchAll function)
	 */
	public $lines = array();
	/**
	 * @var int Total number of records (used in fetchAll function)
	 */
	public $total = 0;
	/**
	 * @var int Total number of fetched records (used in fetchAll function)
	 */
	public $count = 0;
	/**
	 * @var string Triggers prefix
	 */
	public $triggers_prefix;


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		global $db;

		$this->db = $db;
		$this->triggers_prefix = empty($this->element) ? get_rights_class(true) : strtoupper($this->element);
	}

	/**
	 * Set table name
	 * 
	 * @param  $table_name  table name
	 */
	public function setTableName($table_name)
	{
		$this->table_element = $table_name;
	}

	/**
	 * Create object into database
	 *
	 * @param  array  $data array, e.: array('my_field_name' => 'my_field_value', 'second_field_name' => 'second_field_value')
	 * @param  int    $notrigger 0=launch triggers after, 1=disable triggers
	 * @return int    <0 if KO, Id of created object if OK
	 */
	public function create($data, $notrigger = 1)
	{
		$error = 0;

		// INSERT request
		$sql = "INSERT INTO " . MAIN_DB_PREFIX . $this->table_element . "(";
		foreach ($data as $key => $value) {
			$sql.= "`" . $key . "`,";
		}
		$sql = substr($sql, 0, -1); // Remove the last ','

		$sql.= ") VALUES (";
		foreach ($data as $key => $value) {
			$sql.= $this->escape($value) . ",";
		}
		$sql = substr($sql, 0, -1); // Remove the last ','

		$sql.= ")";

		$this->db->begin();

		dol_syslog(__METHOD__ . " sql=" . $sql, LOG_DEBUG);
		$resql = $this->db->query($sql);
		if (! $resql) {
			$error ++;
			$this->errors[] = "Error " . $this->db->lasterror();
		}

		if (! $error) {
			$this->id = $this->db->last_insert_id(MAIN_DB_PREFIX . $this->table_element, $this->pk_name);

			if (! $notrigger) {
				$error = $this->run_triggers('_CREATE');
			}
		}

		// Commit or rollback
		if ($error) {
			foreach ($this->errors as $errmsg) {
				dol_syslog(__METHOD__ . " " . $errmsg, LOG_ERR);
				$this->error.=($this->error ? ', ' . $errmsg : $errmsg);
			}
			$this->db->rollback();
			setEventMessage($this->error, 'errors');

			return -1 * $error;
		} else {
			$this->db->commit();

			return $this->id;
		}
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
		// SELECT request
		$sql = "SELECT ";
		foreach ($this->fetch_fields as $field) {
			$sql.= "`" . $field . "`,";
		}
		$sql = substr($sql, 0, -1); // Remove the last ','
		$sql.= " FROM " . MAIN_DB_PREFIX . $this->table_element;
		$sql.= " WHERE ";
		if (! empty($ref)) {
			$sql.= $this->ref_field_name . " = '" . $ref . "'";
		}
		else {
			$sql.= $this->pk_name . " = " . $id;
		}

		dol_syslog(__METHOD__ . " sql=" . $sql, LOG_DEBUG);
		$resql = $this->db->query($sql);
		if ($resql) {
			if ($this->db->num_rows($resql)) {
				$obj = $this->db->fetch_object($resql);

				foreach ($this->fetch_fields as $field) {
					$this->$field = $obj->$field;
				}

				// enssure that $this->id is filled because we use it in update & delete functions
				if (! in_array('id', $this->fetch_fields)) {
					$this->id = $obj->{$this->pk_name};
				}

				// Fix error: dol_print_date function call with deprecated value of time
				foreach ($this->date_fields as $field) {
					$this->$field = $this->db->jdate($this->$field);
				}

				$this->db->free($resql);

				return 1;
			}
			$this->db->free($resql);

			return 0;
		} else {
			$this->error = "Error " . $this->db->lasterror();
			dol_syslog(__METHOD__ . " " . $this->error, LOG_ERR);
			setEventMessage($this->error, 'errors');

			return -1;
		}
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
		// Init lines
		$this->lines = array();

		if (empty($this->fetch_fields)) {
			return 0;
		}

		// SELECT request
		$sql = "SELECT DISTINCT ";
		foreach ($this->fetch_fields as $field) {
			$sql.= (! empty($table_alias) ? $table_alias.'.' : '')."`" . $field . "`,";
		}
		$sql = substr($sql, 0, -1); // Remove the last ','
		if (! empty($more_fields)) {
			$sql.= $more_fields[0] == ',' ? $more_fields : ', ' . $more_fields;
		}
		$sql.= " FROM " . MAIN_DB_PREFIX . $this->table_element;
		if (! empty($table_alias)) $sql.= " as ".$table_alias;
		if (! empty($join)) $sql.= $join;
		if (! empty($where)) $sql.= " WHERE ".$where;
		if (! empty($sort_field)) $sql.= $this->db->order($sort_field, $sort_order);
		if ($get_total) {
			global $conf;
			$this->total = 0;
			if (empty($conf->global->MAIN_DISABLE_FULL_SCANLIST))
			{
				$result = $this->db->query($sql);
				if ($result) {
					$this->total = $this->db->num_rows($result);
				}
			}
		}
		if ($limit > 0) {
			if ($get_total) {
				$sql.= $this->db->plimit($limit+1, $offset); // for list pagination
			}
			else {
				$sql.= $this->db->plimit($limit, $offset);
			}
		}

		dol_syslog(__METHOD__ . " sql=" . $sql, LOG_DEBUG);
		$resql = $this->db->query($sql);
		if ($resql) {
			$this->count = $this->db->num_rows($resql);
			if ($this->count)
			{
				$i = 0;
				$num = ($get_total ? min($this->count, $limit) : $this->count); // also for list pagination
				$set_id = (! in_array('id', $this->fetch_fields) ? true : false);

				while ($i < $num)
				{
					$obj = $this->db->fetch_object($resql);

					$classname = get_class($this);

					$this->lines[$i] = new $classname();

					foreach ($this->fetch_fields as $field) {
						$this->lines[$i]->$field = $obj->$field;
					}

					if (! empty($more_fields)) 
					{
						$fields = explode(',', $more_fields);

						foreach ($fields as $field) 
						{
							$field = trim($field);

							if (! empty($field))
							{
								// check for ' as ' alias
								$pos = stripos($field, ' as ');
								if ($pos !== false) {
									$field = substr($field, $pos + 4); // 4 == strlen(' as ')
								}
								// add field
								$this->lines[$i]->$field = $obj->$field;
							}
						}
					}

					// enssure that $this->id is filled because we use it in update/delete/getNomUrl functions
					if ($set_id) {
						$this->lines[$i]->id = $obj->{$this->pk_name};
					}

					// Fix error: dol_print_date function call with deprecated value of time
					foreach ($this->date_fields as $field) {
						$this->lines[$i]->$field = $this->db->jdate($this->lines[$i]->$field);
					}

					$i++;
				}

				$this->db->free($resql);

				return 1;
			}
			$this->db->free($resql);

			return 0;
		} else {
			$this->error = "Error " . $this->db->lasterror();
			dol_syslog(__METHOD__ . " " . $this->error, LOG_ERR);
			setEventMessage($this->error, 'errors');

			return -1;
		}
	}

	/**
	 * Load object in memory from database
	 *
	 * @param  array   $fields       fields to fetch
	 * @param  string  $where        where clause (without 'WHERE')
	 * @return int     <0 if KO, >0 if OK
	 */
	public function fetchWhere($fields, $where)
	{
		// SELECT request
		$sql = "SELECT ";
		foreach ($fields as $field) {
			$sql.= "`" . $field . "`,";
		}
		$sql = substr($sql, 0, -1); // Remove the last ','
		$sql.= " FROM " . MAIN_DB_PREFIX . $this->table_element;
		$sql.= " WHERE " . $where;

		dol_syslog(__METHOD__ . " sql=" . $sql, LOG_DEBUG);
		$resql = $this->db->query($sql);
		if ($resql) {
			if ($this->db->num_rows($resql)) {
				$obj = $this->db->fetch_object($resql);

				foreach ($fields as $field) {
					$this->$field = $obj->$field;
				}

				// enssure that $this->id is filled because we use it in update & delete functions
				if (! in_array('id', $fields)) {
					$this->id = $obj->{$this->pk_name};
				}

				$this->db->free($resql);

				return 1;
			}
			$this->db->free($resql);

			return 0;
		} else {
			$this->error = "Error " . $this->db->lasterror();
			dol_syslog(__METHOD__ . " " . $this->error, LOG_ERR);
			setEventMessage($this->error, 'errors');

			return -1;
		}
	}

	/**
	 * Update object into database
	 *
	 * @param  array   $data array, e.: array('my_field_name' => 'my_field_value', 'second_field_name' => 'second_field_value')
	 * @param  int     $notrigger 0=launch triggers after, 1=disable triggers
	 * @return int     <0 if KO, >0 if OK
	 */
	public function update($data, $notrigger = 1)
	{
		$error = 0;

		// UPDATE request
		$sql = "UPDATE " . MAIN_DB_PREFIX . $this->table_element . " SET ";
		foreach ($data as $key => $value) {
			$sql.= "`" . $key . "` = " . $this->escape($value) . ",";
		}
		$sql = substr($sql, 0, -1); // Remove the last ','
		$sql.= " WHERE ".$this->pk_name."=" . $this->id;

		$this->db->begin();

		dol_syslog(__METHOD__ . " sql=" . $sql, LOG_DEBUG);
		$resql = $this->db->query($sql);
		if (! $resql) {
			$error ++;
			$this->errors[] = "Error " . $this->db->lasterror();
		}

		if (! $error) {
			if (! $notrigger) {
				$error = $this->run_triggers('_MODIFY');
			}
		}

		// Commit or rollback
		if ($error) {
			foreach ($this->errors as $errmsg) {
				dol_syslog(__METHOD__ . " " . $errmsg, LOG_ERR);
				$this->error.=($this->error ? ', ' . $errmsg : $errmsg);
			}
			$this->db->rollback();
			setEventMessage($this->error, 'errors');

			return -1 * $error;
		} else {
			$this->db->commit();

			// apply changes to object
			foreach ($data as $key => $value) {
				$this->$key = $value;
			}

			return 1;
		}
	}

	/**
	 * Update row(s) into database (wrapper for updateAll function)
	 *
	 * @param  array   $data      array, e.: array('my_field_name' => 'my_field_value', 'second_field_name' => 'second_field_value')
	 * @param  string  $where     where clause (without 'WHERE')
	 * @param  int     $notrigger 0=launch triggers after, 1=disable triggers
	 * @return int                <0 if KO, >0 if OK
	 */
	public function updateWhere($data, $where, $notrigger = 1)
	{
		return $this->updateAll($data, $where, $notrigger);
	}

	/**
	 * Update all object rows into database
	 *
	 * @param  array   $data      array, e.: array('my_field_name' => 'my_field_value', 'second_field_name' => 'second_field_value')
	 * @param  string  $where     where clause (without 'WHERE')
	 * @param  int     $notrigger 0=launch triggers after, 1=disable triggers
	 * @return int                <0 if KO, >0 if OK
	 */
	public function updateAll($data, $where = '', $notrigger = 1)
	{
		$error = 0;

		// UPDATE request
		$sql = "UPDATE " . MAIN_DB_PREFIX . $this->table_element . " SET ";
		foreach ($data as $key => $value) {
			$sql.= "`" . $key . "` = " . $this->escape($value) . ",";
		}
		$sql = substr($sql, 0, -1); // Remove the last ','
		if (! empty($where)) $sql.= " WHERE ".$where;

		$this->db->begin();

		dol_syslog(__METHOD__ . " sql=" . $sql, LOG_DEBUG);
		$resql = $this->db->query($sql);
		if (! $resql) {
			$error ++;
			$this->errors[] = "Error " . $this->db->lasterror();
		}

		if (! $error) {
			if (! $notrigger) {
				$error = $this->run_triggers('_MODIFY_ALL');
			}
		}

		// Commit or rollback
		if ($error) {
			foreach ($this->errors as $errmsg) {
				dol_syslog(__METHOD__ . " " . $errmsg, LOG_ERR);
				$this->error.=($this->error ? ', ' . $errmsg : $errmsg);
			}
			$this->db->rollback();
			setEventMessage($this->error, 'errors');

			return -1 * $error;
		} else {
			$this->db->commit();

			return 1;
		}
	}

	/**
	 * Delete object in database
	 *
	 * @param  int  $notrigger 0=launch triggers after, 1=disable triggers
	 * @return int  <0 if KO, >0 if OK
	 */
	public function delete($notrigger = 1)
	{
		$error = 0;

		$this->db->begin();

		if (! $error) {
			if (! $notrigger) {
				$error = $this->run_triggers('_DELETE');
			}
		}

		if (! $error) {
			$sql = "DELETE FROM " . MAIN_DB_PREFIX . $this->table_element;
			$sql.= " WHERE ".$this->pk_name."=" . $this->id;

			dol_syslog(__METHOD__ . " sql=" . $sql);
			$resql = $this->db->query($sql);
			if (! $resql) {
				$error ++;
				$this->errors[] = "Error " . $this->db->lasterror();
			}
			else {
				$this->deleteObjectLinked(); // delete linked objects also
			}
		}

		// Commit or rollback
		if ($error) {
			foreach ($this->errors as $errmsg) {
				dol_syslog(__METHOD__ . " " . $errmsg, LOG_ERR);
				$this->error.=($this->error ? ', ' . $errmsg : $errmsg);
			}
			$this->db->rollback();
			setEventMessage($this->error, 'errors');

			return -1 * $error;
		} else {
			$this->db->commit();

			return 1;
		}
	}

	/**
	 * Delete row(s) in database (wrapper for deleteAll function)
	 *
	 * @param  string  $where     where clause (without 'WHERE')
	 * @param  int     $notrigger 0=launch triggers after, 1=disable triggers
	 * @return int                <0 if KO, >0 if OK
	 */
	public function deleteWhere($where, $notrigger = 1)
	{
		return $this->deleteAll($where, $notrigger);
	}

	/**
	 * Delete all object rows in database
	 *
	 * @param  string  $where     where clause (without 'WHERE')
	 * @param  int     $notrigger 0=launch triggers after, 1=disable triggers
	 * @return int                <0 if KO, >0 if OK
	 */
	public function deleteAll($where = '', $notrigger = 1)
	{
		$error = 0;

		$this->db->begin();

		if (! $error) {
			if (! $notrigger) {
				$error = $this->run_triggers('_DELETE_ALL');
			}
		}

		if (! $error) {
			if (empty($where)) {
				// TRUNCATE request
				$sql = "TRUNCATE " . MAIN_DB_PREFIX . $this->table_element;
			}
			else {
				// DELETE request
				$sql = "DELETE FROM " . MAIN_DB_PREFIX . $this->table_element;
				$sql.= " WHERE ".$where;
				// Fetch rows ids before deleting
				$this->fetchAll(0, 0, '', 'DESC', '', '', $where, false, '');
			}

			dol_syslog(__METHOD__ . " sql=" . $sql);
			$resql = $this->db->query($sql);
			if (! $resql) {
				$error ++;
				$this->errors[] = "Error " . $this->db->lasterror();
			}
			else {
				// delete linked objects also
				if (empty($where)) {
					$this->deleteAllObjectLinked();
				}
				else {
					foreach ($this->lines as $obj) {
						$obj->deleteObjectLinked();
					}
				}
			}
		}

		// Commit or rollback
		if ($error) {
			foreach ($this->errors as $errmsg) {
				dol_syslog(__METHOD__ . " " . $errmsg, LOG_ERR);
				$this->error.=($this->error ? ', ' . $errmsg : $errmsg);
			}
			$this->db->rollback();
			setEventMessage($this->error, 'errors');

			return -1 * $error;
		} else {
			$this->db->commit();

			return 1;
		}
	}

	/**
	 * Delete all links between an object $this
	 *
	 * @return     int     <0 if KO, >0 if OK
	 */
	protected function deleteAllObjectLinked()
	{
		$sql = "DELETE FROM ".MAIN_DB_PREFIX."element_element";
		$sql.= " WHERE sourcetype = '".$this->db->escape($this->element)."' OR targettype = '".$this->db->escape($this->element)."'";

		dol_syslog(get_class($this)."::deleteAllObjectLinked", LOG_DEBUG);
		if ($this->db->query($sql))
		{
			return 1;
		}
		else
		{
			$this->error = $this->db->lasterror();
			$this->errors[] = $this->error;
			return -1;
		}
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
	 * Run Dolibarr triggers (from other modules)
	 *
	 * @param      $action_suffix      action suffix
	 * @return     int                 errors number
	 */
	protected function run_triggers($action_suffix)
	{
		global $user, $langs, $conf;

		$error = 0;

		// Call triggers
		require_once DOL_DOCUMENT_ROOT . "/core/class/interfaces.class.php";
		$interface = new Interfaces($this->db);
		$action = $this->triggers_prefix.$action_suffix;
		$result = $interface->run_triggers($action, $this, $user, $langs, $conf);
		if ($result < 0) { $error++; $this->errors = $interface->errors; }
		// End call triggers

		return $error;
	}
}
