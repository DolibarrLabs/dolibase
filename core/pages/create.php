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

dolibase_include_once('core/class/form_page.php');
require_once DOL_DOCUMENT_ROOT . '/core/class/extrafields.class.php';

/**
 * CreatePage class
 */

class CreatePage extends FormPage
{
	/**
	 * @var object extrafields
	 */
	protected $extrafields;


	/**
	 * Constructor
	 * 
	 * @param     $page_title     HTML page title
	 * @param     $access_perm    Access permission
	 */
	public function __construct($page_title, $access_perm = '')
	{
		global $db;

		$this->extrafields = new ExtraFields($db);

		parent::__construct($page_title, $access_perm);
	}

	/**
	 * Check page extrafields
	 *
	 * @param      $object     Object
	 * @return     boolean     true or false
	 */
	public function checkExtraFields($object)
	{
		// fetch optionals attributes and labels
		$extralabels = $this->extrafields->fetch_name_optionals_label($object->table_element);

		// Fill array 'array_options' with data from add form
		$result = $this->extrafields->setOptionalsFromPost($extralabels, $object);

		return $result >= 0 ? true : false;
	}

	/**
	 * Add a table field
	 *
	 * @param     $field_name     field name
	 * @param     $field_content  field content
	 * @param     $is_required    is field required or not
	 * @param     $field_summary  field summary
	 * @param     $more_attr      more attributes to add
	 * @return    $this
	 */
	public function addField($field_name, $field_content, $is_required = false, $field_summary = '', $more_attr = '')
	{
		global $langs;

		echo '<tr>';
		echo '<td width="25%"'.($is_required ? ' class="fieldrequired"' : '').$more_attr.'>' . $langs->trans($field_name) . '</td>';
		echo '<td colspan="2">' . $field_content;
		if (! empty($field_summary)) echo $this->form->textwithpicto(' ', $langs->trans($field_summary));
		echo '</td>';
		echo '</tr>';

		return $this;
	}

	/**
	 * Add a table field with a text input
	 *
	 * @param     $field_name        field name
	 * @param     $input_name        input name
	 * @param     $input_value       input value
	 * @param     $is_required       is field required or not
	 * @param     $field_summary     field summary
	 * @param     $input_size        input size
	 * @return    $this
	 */
	public function addTextField($field_name, $input_name, $input_value = '', $is_required = false, $field_summary = '', $input_size = 20)
	{
		$field_content = $this->form->textInput($input_name, $input_value, $input_size);

		$this->addField($field_name, $field_content, $is_required, $field_summary);

		return $this;
	}

	/**
	 * Add a table field with a text area
	 *
	 * @param     $field_name            field name
	 * @param     $text_area_name        text area name
	 * @param     $text_area_value       text area value
	 * @param     $is_required           is field required or not
	 * @param     $field_summary         field summary
	 * @param     $toolbarname           Editor toolbar name, values: 'Full', dolibarr_details', 'dolibarr_notes', 'dolibarr_mailings', 'dolibarr_readonly'
	 * @param     $height                text area height
	 * @param     $valign                field vertical align
	 * @return    $this
	 */
	public function addTextAreaField($field_name, $text_area_name, $text_area_value = '', $is_required = false, $field_summary = '', $toolbarname = 'dolibarr_details', $height = 100, $valign = 'top')
	{
		$field_content = $this->form->textEditor($text_area_name, $text_area_value, $toolbarname, $height);

		$more_attr = ' valign="'.$valign.'"';

		$this->addField($field_name, $field_content, $is_required, $field_summary, $more_attr);

		return $this;
	}

	/**
	 * Add a table field with a number input
	 *
	 * @param     $field_name        field name
	 * @param     $input_name        input name
	 * @param     $input_value       input value
	 * @param     $is_required       is field required or not
	 * @param     $field_summary     field summary
	 * @param     $min               input minimum number
	 * @param     $max               input maximum number
	 * @return    $this
	 */
	public function addNumberField($field_name, $input_name, $input_value = '', $is_required = false, $field_summary = '', $min = 0, $max = 100)
	{
		$input_value = (empty($input_value) ? $min : $input_value);

		$field_content = $this->form->numberInput($input_name, $input_value, $min, $max);

		$this->addField($field_name, $field_content, $is_required, $field_summary);

		return $this;
	}

	/**
	 * Add a table field with a date picker
	 *
	 * @param     $field_name        field name
	 * @param     $input_name        input name
	 * @param     $input_value       input value
	 * @param     $is_required       is field required or not
	 * @param     $field_summary     field summary
	 * @return    $this
	 */
	public function addDateField($field_name, $input_name, $input_value = '', $is_required = false, $field_summary = '')
	{
		$field_content = $this->form->dateInput($input_name, $input_value);

		$this->addField($field_name, $field_content, $is_required, $field_summary);

		return $this;
	}

	/**
	 * Add a table field with a list
	 *
	 * @param     $field_name       field name
	 * @param     $list_name        list name
	 * @param     $list_choices     list choices, e.: array('choice_1' => 'Choice 1', 'choice_2' => 'Choice 2')
	 * @param     $selected_choice  selected choice
	 * @param     $is_required      is field required or not
	 * @param     $field_summary    field summary
	 * @param     $show_empty       show empty value
	 * @return    $this
	 */
	public function addListField($field_name, $list_name, $list_choices, $selected_choice = '', $is_required = false, $field_summary = '', $show_empty = 0)
	{
		$field_content = $this->form->listInput($list_name, $list_choices, $selected_choice, $show_empty);

		$this->addField($field_name, $field_content, $is_required, $field_summary);

		return $this;
	}

	/**
	 * Add a table field with a radio input(s)
	 *
	 * @param     $field_name       field name
	 * @param     $radio_name       radio inputs name
	 * @param     $radio_list       list of radio inputs, e.: array('radio_1' => 'Radio 1', 'radio_2' => 'Radio 2')
	 * @param     $selected         selected radio input
	 * @param     $is_required      is field required or not
	 * @param     $field_summary    field summary
	 * @param     $valign           field vertical align
	 * @return    $this
	 */
	public function addRadioListField($field_name, $radio_name, $radio_list, $selected = '', $is_required = false, $field_summary = '', $valign = 'middle')
	{
		$field_content = $this->form->radioList($radio_name, $radio_list, $selected);

		$more_attr = ' valign="'.$valign.'"';

		$this->addField($field_name, $field_content, $is_required, $field_summary, $more_attr);

		return $this;
	}

	/**
	 * Add a table field with a checkbox input(s)
	 *
	 * @param     $field_name       field name
	 * @param     $check_name       checkbox inputs name
	 * @param     $check_list       list of checkbox inputs, e.: array('check_1' => 'Checkbox 1', 'check_2' => 'Checkbox 2')
	 * @param     $selected         selected checkbox input
	 * @param     $is_required      is field required or not
	 * @param     $field_summary    field summary
	 * @param     $valign           field vertical align
	 * @return    $this
	 */
	public function addCheckListField($field_name, $check_name, $check_list, $selected = '', $is_required = false, $field_summary = '', $valign = 'middle')
	{
		$field_content = $this->form->checkList($check_name, $check_list, $selected);

		$more_attr = ' valign="'.$valign.'"';

		$this->addField($field_name, $field_content, $is_required, $field_summary, $more_attr);

		return $this;
	}

	/**
	 * Add a table field with a color picker
	 *
	 * @param     $field_name        field name
	 * @param     $input_name        input name
	 * @param     $input_value       input value
	 * @param     $is_required       is field required or not
	 * @param     $field_summary     field summary
	 * @return    $this
	 */
	public function addColorField($field_name, $input_name, $input_value = '', $is_required = false, $field_summary = '')
	{
		$field_content = $this->form->colorInput($input_name, $input_value);
		
		$this->addField($field_name, $field_content, $is_required, $field_summary);

		return $this;
	}

	/**
	 * Add extra fields
	 *
	 * @param      $object     Object
	 * @return     $this
	 */
	public function addExtraFields($object)
	{
		global $hookmanager, $action;

		// fetch optionals attributes and labels
		$extralabels = $this->extrafields->fetch_name_optionals_label($object->table_element);

		// show attributes
		$parameters = array();
		$reshook = $hookmanager->executeHooks('formObjectOptions', $parameters, $object, $action);
		echo $hookmanager->resPrint;
		if (empty($reshook) && ! empty($this->extrafields->attribute_label)) {
			echo $object->showOptionals($this->extrafields, 'edit');
		}

		return $this;
	}

	/**
	 * Generate form buttons
	 *
	 * @return    $this
	 */
	public function generateFormButtons()
	{
		global $langs;

		echo '<div class="center">';
		echo '<input type="submit" class="button" value="' . $langs->trans('Create') . '">';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		echo '<input type="button" class="button" value="' . $langs->trans('Cancel') . '" onClick="javascript:history.go(-1)">';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		echo '<input type="reset" class="button" value="' . $langs->trans('Reset') . '">';
		echo '</div>';

		return $this;
	}
}
