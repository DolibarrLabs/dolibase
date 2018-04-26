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
		$this->head.= "<style>
						.dolibase_radio {
							height: 26px !important;
	                        vertical-align: middle;
	                    }
	                </style>";

        // Add custom js
		$this->head.= empty($conf->use_javascript_ajax) ? "" : "<script type=\"text/javascript\">
						$(document).ready(function () {
							$('.dolibase_select').select2({
					            dir: 'ltr',
					            width: 'resolve',       /* off or resolve */
					            minimumInputLength: 0
					        });
					    });
					</script>";

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

		print $this->body;
	}
}