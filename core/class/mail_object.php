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
 * MailObject class
 */

class MailObject extends CommonObject
{
	/**
	 * @var DoliDb Database handler
	 */
	public $db;
	/**
	 * @var int An example ID
	 */
	public $id = 0;
	/**
	 * @var string element
	 */
	public $element = '';


	/**
	 * Constructor
	 *
	 * @param DoliDb $db Database handler
	 */
	public function __construct($db)
	{
		$this->db = $db;
	}

	/**
	 * Load object in memory from database
	 *
	 * @param  int $id object Id
	 * @return int <0 if KO, >0 if OK
	 */
	public function fetch($id)
	{
		return 1; // fix mail bug on dolibarr 3.9
	}
}
