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
	public $fetch_fields = array(); // e.: array('field_1', 'field_2', 'field_3')
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
			if (DOLIBASE_DEBUG_MODE) dol_print_error($this->db, $this->error);
    		else setEventMessage($this->error, 'errors');

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
				if (! isset($this->id)) {
					$id_field_name = $this->pk_name;
					$this->id = $obj->$id_field_name;
				}

				return 1;
			}
			$this->db->free($resql);

			return 0;
		} else {
			$this->error = "Error " . $this->db->lasterror();
			dol_syslog(__METHOD__ . " " . $this->error, LOG_ERR);
			if (DOLIBASE_DEBUG_MODE) dol_print_error($this->db, $this->error);
    		else setEventMessage($this->error, 'errors');

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
	 * @return int                   <0 if KO, >0 if OK
	 */
	public function fetchAll($limit = 0, $offset = 0, $sort_field = '', $sort_order = 'DESC', $more_fields = '', $join = '', $where = '', $get_total = false)
	{
		// SELECT request
		$sql = "SELECT ";
		foreach ($this->fetch_fields as $field) {
			$sql.= "t.`" . $field . "`,";
		}
		if (empty($more_fields)) $sql = substr($sql, 0, -1); // Remove the last ','
		else $sql.= $more_fields;
		$sql.= " FROM " . MAIN_DB_PREFIX . $this->table_element . " as t";
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
				$this->lines = array();
				$num = ($get_total ? min($this->count, $limit) : $this->count); // also for list pagination

				while ($i < $num)
				{
					$obj = $this->db->fetch_object($resql);

					$classname = get_class($this);

					$this->lines[$i] = new $classname();

					foreach ($this->fetch_fields as $field) {
						$this->lines[$i]->$field = $obj->$field;
					}

					// enssure that $this->id is filled because we use it in update/delete/getNomUrl functions
					if (! isset($this->lines[$i]->id)) {
						$id_field_name = $this->pk_name;
						$this->lines[$i]->id = $obj->$id_field_name;
					}

					$i++;
				}

				return 1;
			}
			$this->db->free($resql);

			return 0;
		} else {
			$this->error = "Error " . $this->db->lasterror();
			dol_syslog(__METHOD__ . " " . $this->error, LOG_ERR);
			if (DOLIBASE_DEBUG_MODE) dol_print_error($this->db, $this->error);
    		else setEventMessage($this->error, 'errors');

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
			if (DOLIBASE_DEBUG_MODE) dol_print_error($this->db, $this->error);
    		else setEventMessage($this->error, 'errors');

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
			if (DOLIBASE_DEBUG_MODE) dol_print_error($this->db, $this->error);
    		else setEventMessage($this->error, 'errors');

			return -1 * $error;
		} else {
			$this->db->commit();

			return 1;
		}
	}

	/**
	 *  Returns the reference to the following non used object depending on the active numbering model
	 *  defined into MODULE_RIGHTS_CLASS_ADDON
	 *
	 *  @param	Societe		$soc  	Object thirdparty
	 *  @return string      		Reference
	 */
    public function getNextNumRef($soc = '')
    {
        global $conf, $langs, $dolibase_config;

        $const_name = strtoupper($dolibase_config['rights_class']) . '_ADDON';

        if (! empty($conf->global->$const_name))
        {
        	$mybool=false;

            $file = $conf->global->$const_name;
            $classname = 'NumModel'.ucfirst($file);

            // Include file with class
            $dirmodels = array_merge(array('/'), (array) $conf->modules_parts['models']);
            foreach ($dirmodels as $reldir) {

                $dir = dol_buildpath($reldir."/dolibase/core/num_models/");
				$mod_dir = dol_buildpath($reldir."/".$dolibase_config['module_folder']."/dolibase/core/num_models/");
				
				$dir = ! is_dir($dir) ? $mod_dir : $dir;

                // Load file with numbering class (if found)
                $mybool|=@include_once $dir.$file.".php";
            }

            if (! $mybool)
            {
                dol_print_error('',"Failed to include file ".$file);
                return '';
            }

            $obj = new $classname();
            $numref = "";
            $numref = $obj->getNextValue($soc);

            if ($numref != "")
            {
                return $numref;
            }
            else
            {
                $this->error = $obj->error;
                setEventMessage($this->error, 'errors');
                return "";
            }
        }
        else
        {
            $langs->load("errors");
            $this->error = $langs->trans("ErrorModuleSetupNotComplete");
            setEventMessage($this->error, 'errors');
            return "";
        }
    }

    /**
    *	Return clicable name (with picto eventually)
    *
    *	@param		int		$withpicto		0=No picto, 1=Include picto into link, 2=Only picto
    *	@param		string	$title			Tooltip title
    *	@return		string					Chain with URL
    */
    public function getNomUrl($withpicto = 0, $title = '')
    {
        global $langs, $dolibase_config;

        $ref_field = $this->ref_field_name;

        $result = '';
        $label  = (! empty($title) ? '<u>' . $langs->trans($title) . '</u><br>' : '');
        if (! empty($this->$ref_field)) {
            $label .= '<b>' . $langs->trans('Ref') . ':</b> ' . $this->$ref_field;
        }
        
        $link = '<a href="'.dol_buildpath('/'.$dolibase_config['module_folder'].'/card.php?id='.$this->id, 1).'" title="'.dol_escape_htmltag($label, 1).'" class="classfortooltip">';
        $linkend = '</a>';

        $picto = $dolibase_config['module_picture'].'@'.$dolibase_config['module_folder'];

        if ($withpicto) $result.= ($link.img_object($label, $picto, 'class="classfortooltip"').$linkend);
        if ($withpicto && $withpicto != 2) $result.= ' ';
        $result.= $link.$this->$ref_field.$linkend;
        
        return $result;
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