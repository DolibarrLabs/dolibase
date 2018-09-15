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

dolibase_include_once('/core/class/page.php');
dolibase_include_once('/core/class/custom_form.php');
dolibase_include_once('/core/class/field.php');

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
	 * @var array Fields to check on validation
	 */
	public $fields = array();


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
		$this->appendToHead('<link rel="stylesheet" type="text/css" href="'.dolibase_buildurl('/core/css/form.css.php').'">'."\n");

		// Add custom js
		if ($conf->use_javascript_ajax) {
			$this->appendToHead('<script type="text/javascript" src="'.dolibase_buildurl('/core/js/form.js.php').'"></script>'."\n");
		}

		parent::__construct($page_title, $access_perm);
	}

	/**
	 * Check page fields
	 *
	 * @return     boolean     true or false
	 */
	public function checkFields()
	{
		$error = 0;

		foreach($this->fields as $field) {
			$error += $this->checkField($field->name, $field->trans, $field->validation_rules, true);
		}

		return $error > 0 ? false : true;
	}

	/**
	 * Check specified field
	 *
	 * @param      $field_name                 Field name
	 * @param      $field_trans                Field translation
	 * @param      $field_validation_rules     Field validatin rules
	 * @param      $return_err_number          return errors number or boolean value
	 * @return     boolean|int                 true/false | errors number
	 */
	public function checkField($field_name, $field_trans = '', $field_validation_rules = '', $return_err_number = false)
	{
		global $langs;

		$langs->load("errors");

		$error = 0;

		$field_value = GETPOST($field_name);

		if (empty($field_trans) || empty($field_validation_rules)) {
			$field = $this->getField($field_name);

			if (empty($field)) {
				return 1;
			}
			else {
				$field_trans = $field->trans;
				$field_validation_rules = $field->validation_rules;
			}
		}

		$validation_rules = explode('|', $field_validation_rules);

		// required
		$is_required = in_array('required', $validation_rules);
		if ($is_required && $field_value == '') {
			setEventMessage($langs->transnoentities("ErrorFieldRequired", $langs->transnoentities($field_trans)), 'errors');
			$error++;
		}

		// numeric (escape if empty)
		else if (in_array('numeric', $validation_rules) && $field_value != '' && ! is_numeric($field_value)) {
			setEventMessage($langs->transnoentities("ErrorFieldFormat", $langs->transnoentities($field_trans)), 'errors');
			$error++;
		}

		// greaterThanZero
		else if (in_array('greaterThanZero', $validation_rules) && $field_value != '' && (! is_numeric($field_value) || $field_value <= 0)) {
			$error_msg = ($is_required ? "ErrorFieldRequired" : "ErrorFieldFormat");
			setEventMessage($langs->transnoentities($error_msg, $langs->transnoentities($field_trans)), 'errors');
			$error++;
		}

		if ($return_err_number) {
			return $error;
		}
		else {
			return $error > 0 ? false : true;
		}
	}

	/**
	 * Return specified field if found
	 *
	 * @return     Field|empty     field object or empty value
	 */
	protected function getField($field_name)
	{
		foreach($this->fields as $field) {
			if ($field->name == $field_name) {
				return $field;
			}
		}

		return '';
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
		global $langs, $dolibase_config;

		$this->body = $this->form->formconfirm($url, $langs->trans($title), $langs->trans($question, $question_param), $action, '', '', $dolibase_config['main']['use_ajax_on_confirm']);
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