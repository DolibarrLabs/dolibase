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

dolibase_include_once('/core/class/num_model.php');

/**
 * NumModelMarbre class
 *
 * Class to manage module numbering rules Marbre
 */

class NumModelMarbre extends NumModel
{
	public $version       = 'dolibarr'; // 'development', 'experimental', 'dolibarr'
	public $nom           = 'Marbre';
	protected $const_name = '';
	protected $table_name = '';
	protected $field_name = 'ref';
	protected $prefix     = '';

	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		global $dolibase_config;

		// Generate constant name
		$this->const_name = strtoupper($dolibase_config['rights_class']) . '_MARBRE_MASK';

		// Set parameters
		$this->table_name = $dolibase_config['num_model_table'];
		$this->field_name = $dolibase_config['num_model_field'];
		$this->prefix     = $dolibase_config['num_model_prefix'];
	}

	/**
     *  Return description of numbering model
     *
     *  @return     string      Text with description
     */
    public function info()
    {
    	global $langs;

      	return $langs->trans("SimpleNumRefModelDesc", $this->prefix);
    }

	/**
	 *  Return an example of numbering
	 *
	 *  @return     string      Example
	 */
	public function getExample()
	{
		return $this->prefix."1801-0001";
	}

	/**
	 *  Check if the numbers already existing in the database doesn't have conflicts with this numbering model
	 *
	 *  @return     boolean     false if conflict, true if ok
	 */
	public function canBeActivated()
	{
		global $conf, $langs, $db;

		$coyymm = ''; $max = '';

		$posindice = 8;
		$sql = "SELECT MAX(CAST(SUBSTRING(".$this->field_name." FROM ".$posindice.") AS SIGNED)) as max";
		$sql.= " FROM ".MAIN_DB_PREFIX.$this->table_name;
		$sql.= " WHERE ".$this->field_name." LIKE '".$this->prefix."____-%'";
		//$sql.= " AND entity = ".$conf->entity;

		$resql=$db->query($sql);
		if ($resql)
		{
			$row = $db->fetch_row($resql);
			if ($row) { $coyymm = substr($row[0],0,6); $max=$row[0]; }
		}
		if ($coyymm && ! preg_match('/'.$this->prefix.'[0-9][0-9][0-9][0-9]/i',$coyymm))
		{
			$langs->load("errors");
			$this->error = $langs->trans('ErrorNumRefModel', $max);
			return false;
		}

		return true;
	}

	/**
	 * 	Return next free value
	 *
	 *  @param	Societe		$objsoc     Object thirdparty
	 *  @param  Object		$object		Object we need next value for
	 *  @return string      			Value if KO, <0 if KO
	 */
	public function getNextValue($objsoc = '', $object = '')
	{
		global $db, $conf;

		// Get max value
		$posindice = 8;
		$sql = "SELECT MAX(CAST(SUBSTRING(".$this->field_name." FROM ".$posindice.") AS SIGNED)) as max";
		$sql.= " FROM ".MAIN_DB_PREFIX.$this->table_name;
		$sql.= " WHERE ".$this->field_name." LIKE '".$this->prefix."____-%'";
		//$sql.= " AND entity = ".$conf->entity;

		$resql = $db->query($sql);
		if ($resql)
		{
			$obj = $db->fetch_object($resql);
			if ($obj) $max = intval($obj->max);
			else $max = 0;
		}
		else
		{
			dol_syslog($this->const_name."::getNextValue", LOG_DEBUG);
			return -1;
		}

		$date = time();
		$yymm = strftime("%y%m",$date);

		if ($max >= (pow(10, 4) - 1)) $num = $max+1; // If counter > 9999, we do not format on 4 chars, we take number as it is
		else $num = sprintf("%04s", $max+1);

		dol_syslog($this->const_name."::getNextValue return ".$this->prefix.$yymm."-".$num);
		return $this->prefix.$yymm."-".$num;
	}
}