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

dolibase_include_once('/core/pages/create.php');

/**
 * CreatePage class
 */

class CardPage extends CreatePage
{
	/**
	 * @var string Edit permission
	 */
	protected $edit_permission = '';
	/**
	 * @var string Delete permission
	 */
	protected $delete_permission = '';
	/**
	 * @var boolean used to close opened buttons div
	 */
	protected $close_buttons_div = false;
	/**
	 * @var string Page body (used to display actions confirmation)
	 */
	protected $body = '';


	/**
	 * Constructor
	 * 
	 * @param     $page_title     HTML page title
	 * @param     $access_perm    Access permission
	 * @param     $edit_perm      Edit permission
	 * @param     $delete_perm    Delete permission
	 */
	public function __construct($page_title, $access_perm = '', $edit_perm = '', $delete_perm = '')
	{
		$this->edit_permission   = $edit_perm;
		$this->delete_permission = $delete_perm;

		parent::__construct($page_title, $access_perm);
	}

	/**
	 * Return if the current user can edit the page or not
	 *
	 * @return     boolean     true or false
	 */
	public function canEdit()
	{
		return empty($this->edit_permission) || verifCond($this->edit_permission);
	}

	/**
	 * Return if the current user can delete the object or not
	 *
	 * @return     boolean     true or false
	 */
	public function canDelete()
	{
		return empty($this->delete_permission) || verifCond($this->delete_permission);
	}

	/**
	 * Add a button to the page
	 *
	 * @param     $name                 button name
	 * @param     $href                 button href
	 * @param     $target               button target
	 * @param     $class                button class
	 * @param     $close_parent_div     should close parent div or not
	 */
	public function addButton($name, $href = '#', $target = '_self', $class = 'butAction', $close_parent_div = false)
	{
		global $langs;

		if (! $this->close_buttons_div) {
			dol_fiche_end();
			print '<div class="tabsAction">';
			$this->close_buttons_div = true;
		}

		print '<a class="'.$class.'" href="'.$href.'" target="'.$target.'">'.$langs->trans($name).'</a>';

		if ($close_parent_div) {
			print '</div>';
			$this->close_buttons_div = false;
		}
	}

	/**
	 * show a table field
	 *
	 * @param     $field_name     field name
	 * @param     $field_content  field content
	 * @param     $is_editable    is field editable or not
	 * @param     $edit_link      edition link
	 */
	public function showField($field_name, $field_content, $is_editable = false, $edit_link = '')
	{
		global $langs;

		print '<tr>';
		print '<td width="25%"><table class="nobordernopadding" width="100%"><tr>';
		print '<td>' . $langs->trans($field_name) . '</td>';
		if ($is_editable && (empty($this->edit_permission) || verifCond($this->edit_permission))) {
			print '<td align="right"><a href="' . $edit_link . '">' . img_edit($langs->trans('Modify'), 1) . '</a></td>';
		}
		print '</tr></table></td>';
		print '<td colspan="5">' . $field_content . '</td>';
		print '</tr>';
	}

	/**
	 * show reference/Ref. field
	 *
	 * @param     $field_name     field name
	 * @param     $object         object
	 * @param     $list_link      link to list
	 */
	public function showRefField($field_name, $object, $list_link = '')
	{
		global $langs;

		$morehtml = (empty($list_link) ? '' : '<a href="'.dol_buildpath($list_link, 1).'">'.$langs->trans("BackToList").'</a>');
		$field_content = $this->form->showrefnav($object, $object->ref_field_name, $morehtml, 1, $object->ref_field_name, $object->ref_field_name);

		$this->showField($field_name, $field_content);
	}

	/**
	 * Edit a field
	 *
	 * @param     $field_name     field name
	 * @param     $field_content  field content
	 * @param     $action_name    action name
	 */
	public function editField($field_name, $field_content, $action_name)
	{
		global $langs;

		$id = GETPOST('id', 'int');

		print '<tr>';
		print '<td width="25%">' . $langs->trans($field_name) . '</td>';
		print '<td colspan="5">';
		print '<form action="' . $_SERVER["PHP_SELF"] . '?id=' . $id . '" method="post">';
        print '<input type="hidden" name="token" value="' . $_SESSION ['newtoken'] . '">';
        print '<input type="hidden" name="action" value="'.$action_name.'">';
        print $field_content;
        print ' <input type="submit" class="button" value="' . $langs->trans('Modify') . '">';
        print '</form>';
		print '</td>';
		print '</tr>';
	}

	/**
	 * add a table field with a text input
	 *
	 * @param     $field_name        field name
	 * @param     $input_name        input name
	 * @param     $input_value       input value
	 * @param     $input_size        input size
	 * @param     $action_prefix     action prefix
	 */
	public function editTextField($field_name, $input_name, $input_value = '', $input_size = 20, $action_prefix = 'set_')
	{
		$field_content = '<input size="'.$input_size.'" type="text" name="'.$input_name.'" value="'.$input_value.'">';
		$this->editField($field_name, $field_content, $action_prefix.$input_name);
	}

	/**
	 * add a table field with a text area
	 *
	 * @param     $field_name            field name
	 * @param     $text_area_name        text area name
	 * @param     $text_area_value       text area value
	 * @param     $toolbarname           Editor toolbar name, values: 'Full', dolibarr_details', 'dolibarr_notes', 'dolibarr_mailings', 'dolibarr_readonly'
	 * @param     $height                text area height
	 * @param     $action_prefix         action prefix
	 */
	public function editTextAreaField($field_name, $text_area_name, $text_area_value = '', $toolbarname = 'dolibarr_details', $height = 100, $action_prefix = 'set_')
	{
		global $conf;

		if (! empty($conf->global->FCKEDITOR_ENABLE_DETAILS_FULL)) $toolbarname = 'Full';
		else if (empty($toolbarname)) $toolbarname = 'dolibarr_details';
	    $doleditor = new DolEditor($text_area_name, $text_area_value, '', $height, $toolbarname, 'In', false, false, true, ROWS_3, '90%');

		$field_content = $doleditor->Create(1);
		//$field_content = '<textarea name="'.$text_area_name.'" wrap="soft" cols="70" fields="'.ROWS_3.'">'.$text_area_value.'</textarea>';
		$this->editField($field_name, $field_content, $action_prefix.$text_area_name);
	}

	/**
	 * add a table field with a number input
	 *
	 * @param     $field_name        field name
	 * @param     $input_name        input name
	 * @param     $input_value       input value
	 * @param     $min               input minimum number
	 * @param     $max               input maximum number
	 * @param     $action_prefix     action prefix
	 */
	public function editNumberField($field_name, $input_name, $input_value = '', $min = 0, $max = 100, $action_prefix = 'set_')
	{
		$field_content = '<input type="number" min="'.$min.'" max="'.$max.'" name="'.$input_name.'" value="'.(empty($input_value) ? $min : $input_value).'">';
		$this->editField($field_name, $field_content, $action_prefix.$input_name);
	}

	/**
	 * add a table field with a date picker
	 *
	 * @param     $field_name        field name
	 * @param     $input_name        input name
	 * @param     $input_value       input value
	 * @param     $action_prefix     action prefix
	 */
	public function editDateField($field_name, $input_name, $input_value = '', $action_prefix = 'set_date_')
	{
		$field_content = $this->form->select_date($input_value, $input_name, 0, 0, 1, '', 1, 1, 1);
		$this->editField($field_name, $field_content, $action_prefix.$input_name);
	}

	/**
	 * add a table field with a list
	 *
	 * @param     $field_name       field name
	 * @param     $list_name        list name
	 * @param     $list_choices     list choices, e.: array('choice_1' => 'Choice 1', 'choice_2' => 'Choice 2')
	 * @param     $selected_choice  selected choice
	 * @param     $action_prefix    action prefix
	 */
	public function editListField($field_name, $list_name, $list_choices, $selected_choice = '', $action_prefix = 'set_')
	{
		global $langs;

		// Translate list choices
		foreach ($list_choices as $key => $value) {
			$list_choices[$key] = $langs->trans($value);
		}

		$field_content = $this->form->selectarray($list_name, $list_choices, $selected_choice);
		$this->editField($field_name, $field_content, $action_prefix.$list_name);
	}

	/**
	 * add a table field with a radio input(s)
	 *
	 * @param     $field_name       field name
	 * @param     $radio_name       radio inputs name
	 * @param     $radio_list       list of radio inputs, e.: array('radio_1' => 'Radio 1', 'radio_2' => 'Radio 2')
	 * @param     $selected         selected radio input
	 * @param     $action_prefix    action prefix
	 */
	public function editRadioListField($field_name, $radio_name, $radio_list, $selected = '', $action_prefix = 'set_')
	{
		global $langs;

		$field_content = '';
		foreach ($radio_list as $key => $value) {
			$field_content.= '<span>';
			$field_content.= '<input type="radio" class="dolibase_radio" name="'.$radio_name.'" id="'.$key.'" value="'.$key.'"'.($selected == $key || ($count == 0 && empty($selected)) ? ' checked' : '').'>';
			$field_content.= ' <label for="'.$key.'">' . $langs->trans($value) . '</label>';
			$field_content.= '</span>';
			$field_content.= "<br>\n";
		}

		$this->editField($field_name, $field_content, $action_prefix.$radio_name);
	}

	/**
	 * add a table field with a color picker
	 *
	 * @param     $field_name        field name
	 * @param     $input_name        input name
	 * @param     $input_value       input value
	 * @param     $action_prefix     action prefix
	 */
	public function editColorField($field_name, $input_name, $input_value = '', $action_prefix = 'set_')
	{
		$field_content = $this->formother->selectColor(colorArrayToHex(colorStringToArray($input_value, array()), ''), $input_name, 'formcolor', 1);
		$this->editField($field_name, $field_content, $action_prefix.$input_name);
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
	 * Generate page body
	 *
	 */
	protected function generate()
	{
		parent::generate();

		print $this->body;
	}

	/**
	 * Print related objects block
	 *
	 */
	protected function printRelatedObjects($object)
	{
	    print '<div class="fichecenter hideonprint"><div class="fichehalfleft">';

	    $permissiondellink = $this->canEdit(); // Used by the include of actions_dellink.inc.php

	    include DOL_DOCUMENT_ROOT.'/core/actions_dellink.inc.php'; // Must be include, not include_once

	    // Show links to link elements
	    $linktoelem = $this->form->showLinkToObjectBlock($object);
	    $somethingshown = $this->form->showLinkedObjectBlock($object, $linktoelem);

	    print '</div></div>';
	}

	/**
	 * Generate page end
	 *
	 */
	public function end($object)
	{
		if ($this->close_buttons_div) print '</div>';

		if ($object->id > 0 && (isset($object->socid) || isset($object->fk_soc))) $this->printRelatedObjects($object);

		parent::end();
	}
}