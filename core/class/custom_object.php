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
 * @copyright	Copyright (c) 2018 - 2019, AXeL-dev
 * @license
 * @link
 * 
 */

//require_once DOL_DOCUMENT_ROOT."/core/class/commonobject.class.php";

/**
 * CustomObject class
 */

class CustomObject extends CommonObject
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
	public $fetch_fields = array(); // e.: array('id' => 'rowid', 'var_name' => 'field_name')
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
	}

	/**
	 * Create object into database
	 *
	 * @param  array  data array, e.: array('my_field_name' => 'my_field_value', 'second_field_name' => 'second_field_value')
	 * @param  int    $notrigger 0=launch triggers after, 1=disable triggers
	 * @return int    <0 if KO, Id of created object if OK
	 */
	public function create($data, $notrigger = 0)
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
				// Uncomment this and change MYOBJECT to your own tag if you
				// want this action call a trigger.
				//// Call triggers
				//include_once DOL_DOCUMENT_ROOT . "/core/class/interfaces.class.php";
				//$interface=new Interfaces($this->db);
				//$result=$interface->run_triggers('MYOBJECT_CREATE',$this,$user,$langs,$conf);
				//if ($result < 0) { $error++; $this->errors=$interface->errors; }
				//// End call triggers
			}
		}

		// Commit or rollback
		if ($error) {
			foreach ($this->errors as $errmsg) {
				dol_syslog(__METHOD__ . " " . $errmsg, LOG_ERR);
				$this->error.=($this->error ? ', ' . $errmsg : $errmsg);
			}
			$this->db->rollback();

			return -1 * $error;
		} else {
			$this->db->commit();

			return $this->id;
		}
	}

	/**
	 * Load object in memory from database
	 *
	 * @param  int  $id Id object
	 * @return int  <0 if KO, >0 if OK
	 */
	public function fetch($id)
	{
		// SELECT request
		$sql = "SELECT ";
		foreach ($this->fetch_fields as $key => $value) {
			$sql.= "`" . $value . "`,";
		}
		$sql = substr($sql, 0, -1); // Remove the last ','
		$sql.= " FROM " . MAIN_DB_PREFIX . $this->table_element;
		$sql.= " AND ".$this->pk_name." = " . $id;

		dol_syslog(__METHOD__ . " sql=" . $sql, LOG_DEBUG);
		$resql = $this->db->query($sql);
		if ($resql) {
			if ($this->db->num_rows($resql)) {
				$obj = $this->db->fetch_object($resql);

				foreach ($this->fetch_fields as $key => $value) {
					$this->$key = $obj->$value;
				}

				// assure that $this->id is filled because we use it in update & delete functions
				$id_field_name = $this->pk_name;
				$this->id = $this->$id_field_name;
			}
			$this->db->free($resql);

			return 1;
		} else {
			$this->error = "Error " . $this->db->lasterror();
			dol_syslog(__METHOD__ . " " . $this->error, LOG_ERR);

			return -1;
		}
	}

	/**
	 * Update object into database
	 *
	 * @param  array   data array, e.: array('my_field_name' => 'my_field_value', 'second_field_name' => 'second_field_value')
	 * @param  int     $notrigger 0=launch triggers after, 1=disable triggers
	 * @return int     <0 if KO, >0 if OK
	 */
	public function update($data, $notrigger = 0)
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
				// Uncomment this and change MYOBJECT to your own tag if you
				// want this action call a trigger.
				//// Call triggers
				//include_once DOL_DOCUMENT_ROOT . "/core/class/interfaces.class.php";
				//$interface=new Interfaces($this->db);
				//$result=$interface->run_triggers('MYOBJECT_MODIFY',$this,$user,$langs,$conf);
				//if ($result < 0) { $error++; $this->errors=$interface->errors; }
				//// End call triggers
			}
		}

		// Commit or rollback
		if ($error) {
			foreach ($this->errors as $errmsg) {
				dol_syslog(__METHOD__ . " " . $errmsg, LOG_ERR);
				$this->error.=($this->error ? ', ' . $errmsg : $errmsg);
			}
			$this->db->rollback();

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
	public function delete($notrigger = 0)
	{
		$error = 0;

		$this->db->begin();

		if (! $error) {
			if (! $notrigger) {
				// Uncomment this and change MYOBJECT to your own tag if you
				// want this action call a trigger.
				//// Call triggers
				//include_once DOL_DOCUMENT_ROOT . "/core/class/interfaces.class.php";
				//$interface=new Interfaces($this->db);
				//$result=$interface->run_triggers('MYOBJECT_DELETE',$this,$user,$langs,$conf);
				//if ($result < 0) { $error++; $this->errors=$interface->errors; }
				//// End call triggers
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
		}

		// Commit or rollback
		if ($error) {
			foreach ($this->errors as $errmsg) {
				dol_syslog(__METHOD__ . " " . $errmsg, LOG_ERR);
				$this->error.=($this->error ? ', ' . $errmsg : $errmsg);
			}
			$this->db->rollback();

			return -1 * $error;
		} else {
			$this->db->commit();

			return 1;
		}
	}

	/**
	 * Escape field value
	 *
	 */
	protected function escape($value)
	{
		return is_null($value) ? 'null' : "'".$value."'";
	}
}