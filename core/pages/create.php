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

dolibase_include_once('/core/class/form_page.php');
dolibase_include_once('/core/class/field.php');
include_once DOL_DOCUMENT_ROOT . '/core/class/doleditor.class.php';

/**
 * CreatePage class
 */

class CreatePage extends FormPage
{
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

		// required
		if (preg_match('/\brequired\b/', $field_validation_rules) && $field_value == '') {
            setEventMessage($langs->transnoentities("ErrorFieldRequired",$langs->transnoentities($field_trans)), 'errors');
            $error++;
        }

        // numeric
        else if (preg_match('/\bnumeric\b/', $field_validation_rules) && ! $field_value == '' && ! is_numeric($field_value)) {
            setEventMessage($langs->transnoentities("ErrorFieldFormat",$langs->transnoentities($field_trans)), 'errors');
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
	 * add a table field
	 *
	 * @param     $field_name     field name
	 * @param     $field_content  field content
	 * @param     $is_required    is field required or not
	 * @param     $field_summary  field summary
	 * @param     $more_attr      more attributes to add
	 */
	public function addField($field_name, $field_content, $is_required = false, $field_summary = '', $more_attr = '')
	{
		global $langs;

		print '<tr>';
		print '<td width="25%"'.($is_required ? ' class="fieldrequired"' : '').$more_attr.'>' . $langs->trans($field_name) . '</td>';
		print '<td colspan="2">' . $field_content;
		if (! empty($field_summary)) {
			print '&nbsp;<div style="display: inline-block; vertical-align: middle">';
			print info_admin($langs->trans($field_summary), 1) . '</div>';
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
		$field_content = '<input size="'.$input_size.'" type="text" name="'.$input_name.'" value="'.$input_value.'">';
		$this->addField($field_name, $field_content, $is_required, $field_summary);
	}

	/**
	 * add a table field with a text area
	 *
	 * @param     $field_name            field name
	 * @param     $text_area_name        text area name
	 * @param     $text_area_value       text area value
	 * @param     $is_required           is field required or not
	 * @param     $field_summary         field summary
	 * @param     $toolbarname           Editor toolbar name, values: 'Full', dolibarr_details', 'dolibarr_notes', 'dolibarr_mailings', 'dolibarr_readonly'
	 * @param     $height                text area height
	 * @param     $valign                field vertical align
	 */
	public function addTextAreaField($field_name, $text_area_name, $text_area_value = '', $is_required = false, $field_summary = '', $toolbarname = 'dolibarr_details', $height = 100, $valign = 'top')
	{
		global $conf;

		if (! empty($conf->global->FCKEDITOR_ENABLE_DETAILS_FULL)) $toolbarname = 'Full';
		else if (empty($toolbarname)) $toolbarname = 'dolibarr_details';
	    $doleditor = new DolEditor($text_area_name, $text_area_value, '', $height, $toolbarname, 'In', false, false, true, ROWS_3, '90%');
	    $field_content = $doleditor->Create(1);
	    //$field_content = '<textarea name="'.$text_area_name.'" wrap="soft" cols="70" fields="'.ROWS_3.'">'.$text_area_value.'</textarea>';
		$more_attr = ' valign="'.$valign.'"';
		$this->addField($field_name, $field_content, $is_required, $field_summary, $more_attr);
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
		$field_content = '<input type="number" min="'.$min.'" max="'.$max.'" name="'.$input_name.'" value="'.(empty($input_value) ? $min : $input_value).'">';
		$this->addField($field_name, $field_content, $is_required, $field_summary);
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
		$field_content = $this->form->select_date($input_value, $input_name, 0, 0, 1, '', 1, 1, 1);
		$this->addField($field_name, $field_content, $is_required, $field_summary);
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

		// Translate list choices
		foreach ($list_choices as $key => $value) {
			$list_choices[$key] = $langs->trans($value);
		}

		$field_content = $this->form->selectarray($list_name, $list_choices, $selected_choice);
		$this->addField($field_name, $field_content, $is_required, $field_summary);
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

		$count = 0;
		$field_content = '';
		foreach ($radio_list as $key => $value) {
			if ($count > 0) $field_content.= "<br>\n";
			$field_content.= '<span>';
			$field_content.= '<input type="radio" class="dolibase_radio" name="'.$radio_name.'" id="'.$key.'" value="'.$key.'"'.($selected == $key || ($count == 0 && empty($selected)) ? ' checked' : '').'>';
			$field_content.= ' <label for="'.$key.'">' . $langs->trans($value) . '</label>';
			$field_content.= '</span>';
			$count++;
		}

		$more_attr = ' valign="'.$valign.'"';
		$this->addField($field_name, $field_content, $is_required, $field_summary, $more_attr);
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
		$field_content = $this->formother->selectColor(colorArrayToHex(colorStringToArray($input_value, array()), ''), $input_name, 'formcolor', 1);
		$this->addField($field_name, $field_content, $is_required, $field_summary);
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