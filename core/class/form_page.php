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

dolibase_include_once('/core/class/page.php');
include_once DOL_DOCUMENT_ROOT . '/core/class/html.formother.class.php';
include_once DOL_DOCUMENT_ROOT . '/core/lib/functions2.lib.php';

/**
 * FormPage class
 */

class FormPage extends Page
{
	/**
	 * @var object used to call Dolibarr form functions
	 */
	public $form;
	/**
	 * @var object used to call Dolibarr more form functions like: color picker
	 */
	public $formother;
	

	/**
	 * Constructor
	 * 
	 * @param     $page_title     HTML page title
	 * @param     $access_perm    Access permission
	 */
	public function __construct($page_title, $access_perm = '')
	{
		global $db;

		// Initialise form objects
		$this->form = new Form($db);
		$this->formother = new FormOther($db);

		parent::__construct($page_title, $access_perm);
	}
}