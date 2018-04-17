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
dolibase_include_once('/core/class/field.php');
include_once DOL_DOCUMENT_ROOT . '/core/class/html.formother.class.php';
include_once DOL_DOCUMENT_ROOT . '/core/lib/functions2.lib.php';
include_once DOL_DOCUMENT_ROOT . '/core/class/doleditor.class.php';

/**
 * CreatePage class
 */

class CreatePage extends Page
{
	/**
	 * @var array Fields to check on validation
	 */
	public $fields = array();
	/**
	 * @var object used to call Dolibarr form functions
	 */
	public $form;
	/**
	 * @var object used to call Dolibarr color picker functions
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

		// Add some custom css
		$this->head = "<style>
						.dolibase_radio {
							height: 26px !important;
	                        vertical-align: middle;
	                    }
	                </style>";

		parent::__construct($page_title, $access_perm);
	}

	/**
	 * Check page fields
	 *
	 * @return     boolean     true or false
	 */
	public function checkFields()
	{
		global $langs;

		$langs->load("errors");

		$error = 0;

		foreach($this->fields as $field) {
			$field_value = GETPOST($field->name);

			// required
			if (preg_match('/\brequired\b/', $field->validation_rules) && $field_value == '') {
	            setEventMessage($langs->transnoentities("ErrorFieldRequired",$langs->transnoentities($field->trans)), 'errors');
	            $error++;
	        }

	        // numeric
	        else if (preg_match('/\bnumeric\b/', $field->validation_rules) && ! $field_value == '' && ! is_numeric($field_value)) {
	            setEventMessage($langs->transnoentities("ErrorFieldFormat",$langs->transnoentities($field->trans)), 'errors');
	            $error++;
	        }
		}

		return $error > 0 ? false : true;
	}

	/**
	 * add a table field
	 *
	 * @param     $field_name     field name
	 * @param     $field_value    field value
	 * @param     $is_required    is field required or not
	 * @param     $field_summary  field summary
	 */
	public function addField($field_name, $field_value, $is_required = false, $field_summary = '')
	{
		global $langs;

		print '<tr>';
		print '<td width="25%"'.($is_required ? ' class="fieldrequired"' : '').'>' . $langs->trans($field_name) . '</td>';
		print '<td colspan="2">' . $field_value;
		if (! empty($field_summary)) {
			print ' ' . $langs->trans($field_summary);
		}
		print '</td>';
		print '</tr>';
	}

	/**
	 * add a table field with a text input
	 *
	 * @param     $field_name        field name
	 * @param     $input_name        input name
	 * @param     $input_value       input value
	 * @param     $is_required       is field required or not
	 * @param     $field_summary     field summary
	 * @param     $input_size        input size
	 */
	public function addTextField($field_name, $input_name, $input_value = '', $is_required = false, $field_summary = '', $input_size = 20)
	{
		global $langs;

		print '<tr>';
		print '<td width="25%"'.($is_required ? ' class="fieldrequired"' : '').'>' . $langs->trans($field_name) . '</td>';
		print '<td colspan="2"><input size="'.$input_size.'" type="text" name="'.$input_name.'" value="'.$input_value.'">';
		if (! empty($field_summary)) {
			print ' ' . $langs->trans($field_summary);
		}
		print '</td>';
		print '</tr>';
	}

	/**
	 * add a table field with a text area
	 *
	 * @param     $field_name            field name
	 * @param     $text_area_name        text area name
	 * @param     $text_area_value       text area value
	 * @param     $is_required           is field required or not
	 * @param     $field_summary         field summary
	 * @param     $height                text area height
	 * @param     $valign                field vertical align
	 */
	public function addTextAreaField($field_name, $text_area_name, $text_area_value = '', $is_required = false, $field_summary = '', $height = 80, $valign = 'top')
	{
		global $langs, $conf;

		print '<tr>';
		print '<td valign="'.$valign.'" width="25%"'.($is_required ? ' class="fieldrequired"' : '').'>' . $langs->trans($field_name) . '</td>';
		print '<td colspan="2">';
		if (! empty($field_summary)) {
			print $langs->trans($field_summary) . '<br>';
		}
		if (! empty($conf->global->FCKEDITOR_ENABLE_DETAILS_FULL)) $toolbarname = 'Full';
		else $toolbarname = 'dolibarr_notes';
	    $doleditor = new DolEditor($text_area_name, $text_area_value, '', $height, $toolbarname, 'In', 0, false, true, fieldS_3, '90%');
	    print $doleditor->Create(1);
	    //print '<textarea name="'.$text_area_name.'" wrap="soft" cols="70" fields="'.fieldS_3.'">'.$text_area_value.'</textarea>';
		print '</td>';
		print '</tr>';
	}

	/**
	 * add a table field with a number input
	 *
	 * @param     $field_name        field name
	 * @param     $input_name        input name
	 * @param     $input_value       input value
	 * @param     $is_required       is field required or not
	 * @param     $field_summary     field summary
	 * @param     $min               input minimum number
	 * @param     $max               input maximum number
	 */
	public function addNumberField($field_name, $input_name, $input_value = '', $is_required = false, $field_summary = '', $min = 0, $max = 100)
	{
		global $langs;

		print '<tr>';
		print '<td width="25%"'.($is_required ? ' class="fieldrequired"' : '').'>' . $langs->trans($field_name) . '</td>';
		print '<td colspan="2">';
		print '<input type="number" min="'.$min.'" max="'.$max.'" name="'.$input_name.'" value="'.(empty($input_value) ? $min : $input_value).'">';
		if (! empty($field_summary)) {
			print ' ' . $langs->trans($field_summary);
		}
		print '</td>';
		print '</tr>';
	}

	/**
	 * add a table field with a date picker
	 *
	 * @param     $field_name        field name
	 * @param     $input_name        input name
	 * @param     $input_value       input value
	 * @param     $is_required       is field required or not
	 * @param     $field_summary     field summary
	 */
	public function addDateField($field_name, $input_name, $input_value = '', $is_required = false, $field_summary = '')
	{
		global $langs;

		print '<tr>';
		print '<td width="25%"'.($is_required ? ' class="fieldrequired"' : '').'>' . $langs->trans($field_name) . '</td>';
		print '<td colspan="2">';
		$this->form->select_date($input_value, $input_name, 0, 0, 1, '', 1, 1);
		if (! empty($field_summary)) {
			print ' ' . $langs->trans($field_summary);
		}
		print '</td>';
		print '</tr>';
	}

	/**
	 * add a table field with a list
	 *
	 * @param     $field_name       field name
	 * @param     $list_name        list name
	 * @param     $list_choices     list choices, e.: array('choice_1' => 'Choice 1', 'choice_2' => 'Choice 2')
	 * @param     $selected_choice  selected choice
	 * @param     $is_required      is field required or not
	 * @param     $field_summary    field summary
	 */
	public function addListField($field_name, $list_name, $list_choices, $selected_choice = '', $is_required = false, $field_summary = '')
	{
		global $langs;

		print '<tr>';
		print '<td width="25%"'.($is_required ? ' class="fieldrequired"' : '').'>' . $langs->trans($field_name) . '</td>';
		print '<td colspan="2">';
		// Translate list choices
		foreach ($list_choices as $key => $value) {
			$list_choices[$key] = $langs->trans($value);
		}
		print $this->form->selectarray($list_name, $list_choices, $selected_choice);
		if (! empty($field_summary)) {
			print ' ' . $langs->trans($field_summary);
		}
		print '</td>';
		print '</tr>';
	}

	/**
	 * add a table field with a radio input(s)
	 *
	 * @param     $field_name       field name
	 * @param     $radio_name       radio inputs name
	 * @param     $radio_list       list of radio inputs, e.: array('radio_1' => 'Radio 1', 'radio_2' => 'Radio 2')
	 * @param     $selected         selected radio input
	 * @param     $is_required      is field required or not
	 * @param     $field_summary    field summary
	 * @param     $valign           field vertical align
	 */
	public function addRadioListField($field_name, $radio_name, $radio_list, $selected = '', $is_required = false, $field_summary = '', $valign = 'middle')
	{
		global $langs;

		print '<tr>';
		print '<td valign="'.$valign.'" width="25%"'.($is_required ? ' class="fieldrequired"' : '').'>' . $langs->trans($field_name) . '</td>';
		print '<td colspan="2">';
		if (! empty($field_summary)) {
			print $langs->trans($field_summary) . '<br>';
		}
		$count = 0;
		foreach ($radio_list as $key => $value) {
			if ($count > 0) print "<br>\n";
			print '<span>';
			print '<input type="radio" class="dolibase_radio" name="'.$radio_name.'" id="'.$key.'" value="'.$key.'"'.($selected == $key || ($count == 0 && empty($selected)) ? ' checked' : '').'>';
			print ' <label for="'.$key.'">' . $langs->trans($value) . '</label>';
			print '</span>';
			$count++;
		}
		print '</td>';
		print '</tr>';
	}

	/**
	 * add a table field with a color picker
	 *
	 * @param     $field_name        field name
	 * @param     $input_name        input name
	 * @param     $input_value       input value
	 * @param     $is_required       is field required or not
	 * @param     $field_summary     field summary
	 */
	public function addColorField($field_name, $input_name, $input_value = '', $is_required = false, $field_summary = '')
	{
		global $langs;

		print '<tr>';
		print '<td width="25%"'.($is_required ? ' class="fieldrequired"' : '').'>' . $langs->trans($field_name) . '</td>';
		print '<td colspan="2">';
		print $this->formother->selectColor(colorArrayToHex(colorStringToArray($input_value, array()), ''), $input_name, 'formcolor', 1);
		if (! empty($field_summary)) {
			print ' ' . $langs->trans($field_summary);
		}
		print '</td>';
		print '</tr>';
	}

	/**
	 * generate form buttons
	 *
	 */
	public function generateFormButtons()
	{
		global $langs;

		print '<div class="center">';
	    print '<input type="submit" class="button" value="' . $langs->trans("Create") . '">';
	    print '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	    print '<input type="button" class="button" value="' . $langs->trans("Cancel") . '" onClick="javascript:history.go(-1)">';
	    print '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	    print '<input type="reset" class="button" value="' . $langs->trans("Reset") . '">';
	    print '</div>';
	}
}