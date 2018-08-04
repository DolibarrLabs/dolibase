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
dolibase_include_once('/core/class/custom_form.php');

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
	 * @var string Page body (used to display actions confirmation)
	 */
	protected $body = '';


	/**
	 * Constructor
	 * 
	 * @param     $page_title     HTML page title
	 * @param     $access_perm    Access permission
	 */
	public function __construct($page_title, $access_perm = '')
	{
		global $db, $conf;

		// Initialise form object
		$this->form = new CustomForm($db);

		// Add some custom css
		$this->appendToHead('<link rel="stylesheet" type="text/css" href="'.DOL_URL_ROOT.DOLIBASE_PATH.'/core/css/form.css.php">'."\n");

		// Add custom js
		if ($conf->use_javascript_ajax) {
			$this->appendToHead('<script type="text/javascript" src="'.DOL_URL_ROOT.DOLIBASE_PATH.'/core/js/form.js.php"></script>'."\n");
		}

		parent::__construct($page_title, $access_perm);
	}

	/**
	 * Show a confirmation message
	 *
	 * @param     $url                Page url
	 * @param     $title              Message title
	 * @param     $question           Message question / content
	 * @param     $action             Action to do after confirmation
	 * @param     $question_param     Question parameter
	 */
	public function askForConfirmation($url, $title, $question, $action, $question_param = '')
	{
		global $langs;

		$this->body = $this->form->formconfirm($url, $langs->trans($title), $langs->trans($question, $question_param), $action, '', '', DOLIBASE_USE_AJAX_ON_CONFIRM);
	}

	/**
	 * Append a content to page body
	 *
	 * @param     $content     content to add
	 */
	public function appendToBody($content)
	{
		$this->body = $content;
	}

	/**
	 * Generate page body
	 *
	 */
	protected function generate()
	{
		parent::generate();

		echo $this->body;
	}
}