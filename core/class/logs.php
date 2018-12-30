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

dolibase_include_once('core/class/crud_object.php');

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

		$this->date_fields = array('datec');
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
