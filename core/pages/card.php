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
 * CardPage class
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

		// Add custom css
		$optioncss = GETPOST('optioncss', 'alpha');
		if ($optioncss == 'print') {
			$this->head.= "<style>
							.hideonprint {display: none;}
							.highlightedonprint {
								font-weight: bold;
								background-color: #4897d280;
							}
		                </style>";
        }
		
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
	 * @param     $attr           HTML attributes
	 */
	public function showField($field_name, $field_content, $is_editable = false, $edit_link = '', $attr = '')
	{
		global $langs;

		print '<tr'.(! empty($attr) ? ' '.$attr : '').'>';
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
	 * show banner
	 *
	 * @param     $object         object
	 * @param     $list_link      link to list
	 * @param     $morehtmlleft   more html in the left
	 */
	public function showBanner($object, $list_link = '', $morehtmlleft = '')
	{
		global $langs;

		$morehtml = (empty($list_link) ? '' : '<a href="'.dol_buildpath($list_link, 1).'">'.$langs->trans("BackToList").'</a>');

		dol_banner_tab($object, 'ref', $morehtml, 1, 'ref', 'ref', '', '', 0, $morehtmlleft);

		print '<div class="underbanner clearboth"></div>';
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
		$field_content = $this->form->textInput($input_name, $input_value, $input_size);

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
		$field_content = $this->form->textArea($text_area_name, $text_area_value, $toolbarname, $height);

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
		$input_value = (empty($input_value) ? $min : $input_value);

		$field_content = $this->form->numberInput($input_name, $input_value, $min, $max);

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
		$field_content = $this->form->dateInput($input_name, $input_value);

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
		$field_content = $this->form->listInput($list_name, $list_choices, $selected_choice);

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
		$field_content = $this->form->radioList($radio_name, $radio_list, $selected, true);

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
		$field_content = $this->form->colorInput($input_name, $input_value);
		
		$this->editField($field_name, $field_content, $action_prefix.$input_name);
	}

	/**
	 * Print related objects block
	 *
	 */
	protected function printRelatedObjects($object)
	{
	    print '<div class="fichecenter hideonprint"><div class="fichehalfleft">';

	    $permissiondellink = $this->canEdit(); // Used by the include of actions_dellink.inc.php
	    $action = GETPOST('action', 'alpha');
	    $id = GETPOST('id', 'int');

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
	public function end($object = '')
	{
		if ($this->close_buttons_div) print '</div>';

		if (! empty($object) && (isset($object->socid) || isset($object->fk_soc))) $this->printRelatedObjects($object);

		parent::end();
	}
}