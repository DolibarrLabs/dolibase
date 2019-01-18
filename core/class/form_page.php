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

dolibase_include_once('core/class/page.php');
dolibase_include_once('core/class/custom_form.php');
dolibase_include_once('core/class/field.php');

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
	protected $fields = array();


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

		// Add custom js
		if ($conf->use_javascript_ajax) {
			$this->appendToHead('<script type="text/javascript" src="'.dolibase_buildurl('core/js/form.js.php').'"></script>'."\n");
		}

		parent::__construct($page_title, $access_perm);
	}

	/**
	 * Set page fields
	 *
	 * @since     2.8.1
	 * @param     $fields     array of fields
	 * @return    $this
	 */
	public function setFields($fields)
	{
		$this->fields = $fields;

		return $this;
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
		global $dolibase_config, $langs;

		$langs->load('errors');
		$langs->load('validation@'.$dolibase_config['main']['path']);

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
			setEventMessage($langs->transnoentities('ErrorFieldRequired', $langs->transnoentities($field_trans)), 'errors');
			$error++;
		}

		// numeric (escape if empty)
		else if (in_array('numeric', $validation_rules) && $field_value != '' && ! is_numeric($field_value)) {
			setEventMessage($langs->transnoentities('ErrorFieldMustBeANumeric', $langs->transnoentities($field_trans)), 'errors');
			$error++;
		}

		// string (escape if empty)
		else if (in_array('string', $validation_rules) && $field_value != '' && ! is_string($field_value)) {
			setEventMessage($langs->transnoentities('ErrorFieldFormat', $langs->transnoentities($field_trans)), 'errors');
			$error++;
		}

		// validEmail (escape if empty)
		else if (in_array('validEmail', $validation_rules) && $field_value != '' && ! filter_var($field_value, FILTER_VALIDATE_EMAIL)) {
			setEventMessage($langs->transnoentities('ErrorFieldFormat', $langs->transnoentities($field_trans)), 'errors');
			$error++;
		}

		// validTel (escape if empty)
		else if (in_array('validTel', $validation_rules) && $field_value != '' && ! preg_match('/^[0-9\-\(\)\/\+\s]*$/', $field_value)) {
			setEventMessage($langs->transnoentities('ErrorFieldFormat', $langs->transnoentities($field_trans)), 'errors');
			$error++;
		}

		// validUrl (escape if empty)
		else if (in_array('validUrl', $validation_rules) && $field_value != '' && ! filter_var($field_value, FILTER_VALIDATE_URL)) {
			setEventMessage($langs->transnoentities('ErrorFieldFormat', $langs->transnoentities($field_trans)), 'errors');
			$error++;
		}

		// validID (escape if empty)
		else if (in_array('validID', $validation_rules) && $field_value != '' && is_numeric($field_value) && $field_value <= 0) {
			$error_msg = ($is_required ? 'ErrorFieldRequired' : 'ErrorFieldFormat');
			setEventMessage($langs->transnoentities($error_msg, $langs->transnoentities($field_trans)), 'errors');
			$error++;
		}

		// greaterThan (escape if empty)
		else if (array_match('/^greaterThan\(([0-9]+)\)$/i', $validation_rules, $matches) && $field_value != '' && is_numeric($field_value) && $field_value <= $matches[1]) {
			setEventMessage($langs->transnoentities('ErrorFieldMustBeGreaterThan', $langs->transnoentities($field_trans), $matches[1]), 'errors');
			$error++;
		}

		// lessThan (escape if empty)
		else if (array_match('/^lessThan\(([0-9]+)\)$/i', $validation_rules, $matches) && $field_value != '' && is_numeric($field_value) && $field_value >= $matches[1]) {
			setEventMessage($langs->transnoentities('ErrorFieldMustBeLessThan', $langs->transnoentities($field_trans), $matches[1]), 'errors');
			$error++;
		}

		// minLength (escape if empty)
		else if (array_match('/^minLength\(([0-9]+)\)$/i', $validation_rules, $matches) && $field_value != '' && strlen($field_value) < $matches[1]) {
			setEventMessage($langs->transnoentities('ErrorFieldMustHaveMinLength', $langs->transnoentities($field_trans), $matches[1]), 'errors');
			$error++;
		}

		// maxLength (escape if empty)
		else if (array_match('/^maxLength\(([0-9]+)\)$/i', $validation_rules, $matches) && $field_value != '' && strlen($field_value) > $matches[1]) {
			setEventMessage($langs->transnoentities('ErrorFieldMustHaveMaxLength', $langs->transnoentities($field_trans), $matches[1]), 'errors');
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
	 * @param     $dialog_id_suffix   Dialog id suffix (used to show the dialog without reloading the page)
	 * @return    $this
	 */
	public function askForConfirmation($url, $title, $question, $action, $question_param = '', $dialog_id_suffix = '')
	{
		global $langs, $dolibase_config;

		if (! empty($dialog_id_suffix)) {
			$use_ajax = $dialog_id_suffix;
		}
		else {
			$use_ajax = $dolibase_config['main']['use_ajax_on_confirm'] ? 1 : 0;
		}

		$this->body.= $this->form->formconfirm($url, $langs->trans($title), $langs->trans($question, $question_param), $action, '', '', $use_ajax);

		return $this;
	}

	/**
	 * Append a content to page body
	 *
	 * @param     $content     content to add
	 * @return    $this
	 */
	public function appendToBody($content)
	{
		$this->body.= $content;

		return $this;
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
