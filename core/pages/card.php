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
dolibase_include_once('/core/lib/related_objects.php');

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
	 * @var boolean used to show documents block
	 */
	protected $show_documents = false;
	/**
	 * @var boolean used to add fiche end
	 */
	protected $add_fiche_end = true;


	/**
	 * Constructor
	 * 
	 * @param     $page_title     HTML page title
	 * @param     $access_perm    Access permission
	 * @param     $edit_perm      Edit permission
	 * @param     $delete_perm    Delete permission
	 * @param     $show_documents Show documents/linked files block
	 * @param     $enable_save_as Enable save as feature
	 */
	public function __construct($page_title, $access_perm = '', $edit_perm = '', $delete_perm = '', $show_documents = false, $enable_save_as = false)
	{
		$this->edit_permission   = $edit_perm;
		$this->delete_permission = $delete_perm;
		$this->show_documents    = $show_documents;

		global $langs, $dolibase_path;

		// Load lang files
		$langs->load("card_page@".$dolibase_path);

		// Add CSS files
		$optioncss = GETPOST('optioncss', 'alpha');
		if ($optioncss == 'print') {
			$this->appendToHead('<link rel="stylesheet" type="text/css" href="'.dolibase_buildurl('/core/css/print.css.php').'">'."\n");
		}
		$this->appendToHead('<link rel="stylesheet" type="text/css" href="'.dolibase_buildurl('/core/css/dropdown.css.php').'">'."\n");

		// Add JS files
		$this->appendToHead('<script type="text/javascript" src="'.dolibase_buildurl('/core/js/dropdown.js.php').'"></script>'."\n");
		if ($enable_save_as) {
			$this->appendToHead('<script type="text/javascript" src="'.dolibase_buildurl('/extra/jsPDF/jspdf.min.js').'"></script>'."\n");
			$this->appendToHead('<script type="text/javascript" src="'.dolibase_buildurl('/extra/jsPDF/jspdf.plugin.autotable.min.js').'"></script>'."\n");
			$this->appendToHead('<script type="text/javascript" src="'.dolibase_buildurl('/extra/table2csv/table2csv.js').'"></script>'."\n");
			$this->appendToHead('<script type="text/javascript" src="'.dolibase_buildurl('/core/js/save_as.js.php').'"></script>'."\n");
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
			echo '<div class="tabsAction">';
			$this->close_buttons_div = true;
			$this->add_fiche_end = false;
		}

		echo '<a class="'.$class.'" href="'.$href.'" target="'.$target.'">'.$langs->trans($name).'</a>';

		if ($close_parent_div) {
			echo '</div>';
			$this->close_buttons_div = false;
		}
	}

	/**
	 * Add a list button to the page
	 *
	 * @param     $name                 button name
	 * @param     $buttons              buttons list
	 * @param     $class                button class
	 * @param     $close_parent_div     should close parent div or not
	 */
	public function addListButton($name, $buttons = array(), $class = 'butAction', $close_parent_div = false)
	{
		global $langs;

		if (! $this->close_buttons_div) {
			dol_fiche_end();
			echo '<div class="tabsAction">';
			$this->close_buttons_div = true;
		}

		echo '<div class="dropdown-click">';
		echo '<label class="drop-btn button '.$class.'">'.$langs->trans($name).'&nbsp;&nbsp;<img class="align-middle" title="" alt="" src="'.dolibase_buildurl('/core/img/arrow-down.png').'" /></label>';
		echo '<div class="dropdown-content dropdown-bottom">';

		// buttons list
		foreach ($buttons as $button)
		{
			echo '<a href="'.(isset($button['href']) ? $button['href'] : '#').'"';
			if (isset($button['id'])) {
				echo ' id="'.$button['id'].'"';
			}
			if (isset($button['style'])) {
				echo ' style="'.$button['style'].'"';
			}
			echo '>';
			if (isset($button['picto'])) {
				echo '<img src="'.$button['picto'].'" alt="picto" class="align-middle" width="20" />';
			}
			echo '&nbsp;&nbsp;'.$langs->trans($button['name']);
			echo '</a>';
		}

		echo '</div></div>';

		if ($close_parent_div) {
			echo '</div>';
			$this->close_buttons_div = false;
		}
	}

	/**
	 * Add save as button to the page
	 *
	 * @param     $close_parent_div     should close parent div or not
	 */
	public function addSaveAsButton($close_parent_div = false)
	{
		$buttons = array(
			array('name' => 'CSV', 'picto' => dolibase_buildurl('/core/img/csv.png'), 'id' => 'save_as_csv', 'style' => 'text-align: center;'),
			array('name' => 'PDF', 'picto' => dolibase_buildurl('/core/img/pdf.png'), 'id' => 'save_as_pdf', 'style' => 'text-align: center;')
		);

		$this->addListButton('SaveAs', $buttons, 'butAction', $close_parent_div);
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

		echo '<tr'.(! empty($attr) ? ' '.$attr : '').'>';
		echo '<td width="25%"><table class="nobordernopadding" width="100%"><tr>';
		echo '<td>' . $langs->trans($field_name) . '</td>';
		if ($is_editable && (empty($this->edit_permission) || verifCond($this->edit_permission))) {
			echo '<td align="right"><a href="' . $edit_link . '">' . img_edit($langs->trans('Modify'), 1) . '</a></td>';
		}
		echo '</tr></table></td>';
		echo '<td colspan="5">' . $field_content . '</td>';
		echo '</tr>';
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
	 * show extra fields
	 *
	 * @param      $object     Object
	 */
	public function showExtraFields($object)
	{
		global $db, $conf, $langs, $hookmanager, $user, $action;

		// fetch optionals attributes and labels
		$extrafields = $this->extrafields;
		$extralabels = $extrafields->fetch_name_optionals_label($object->table_element, true);
		$object->fetch_optionals($object->id, $extralabels);
		$object->element = get_rights_class();

		include_once DOL_DOCUMENT_ROOT . '/core/tpl/extrafields_view.tpl.php';
	}

	/**
	 * update extra fields
	 *
	 * @param      $object     Object
	 */
	public function updateExtraFields($object)
	{
		$error = 0;

		// Fill array 'array_options' with data from update form
		$extralabels = $this->extrafields->fetch_name_optionals_label($object->table_element);
		$object->fetch_optionals($object->id, $extralabels);
		$ret = $this->extrafields->setOptionalsFromPost($extralabels, $object, GETPOST('attribute'));
		if ($ret < 0) $error++;
		if (! $error)
		{
			$result = $object->insertExtraFields();
			if ($result < 0)
			{
				setEventMessages($object->error, $object->errors, 'errors');
				$error++;
			}
		}

		return $error;
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

		echo '<div class="underbanner clearboth"></div>';
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

		echo '<tr>';
		echo '<td width="25%">' . $langs->trans($field_name) . '</td>';
		echo '<td colspan="5">';
		echo '<form action="' . $_SERVER["PHP_SELF"] . '?id=' . $id . '" method="post">';
		echo '<input type="hidden" name="token" value="' . $_SESSION ['newtoken'] . '">';
		echo '<input type="hidden" name="action" value="'.$action_name.'">';
		echo $field_content;
		echo ' <input type="submit" class="button" value="' . $langs->trans('Modify') . '">';
		echo '</form>';
		echo '</td>';
		echo '</tr>';
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
	 * @param     $object     Object
	 * Note: To enable the linking feature, you must define a constant in each module as 'your_module_right_class_in_capital_letters'.'_ENABLE_EXPANDED_LINKS'
	 */
	protected function printRelatedObjects($object)
	{
		if (! empty($object) && isset($object->id))
		{
			global $conf, $langs, $dolibase_path;

			$const_name = get_rights_class(true) . '_ENABLE_EXPANDED_LINKS';

			echo '<div class="fichecenter hideonprint"><div class="fichehalfleft">';

			// Dolibase object linking feature
			if ($conf->global->$const_name)
			{
				$langs->load('related_objects@'.$dolibase_path);

				show_related_objects($object);
			}
			// Dolibarr linked objects block
			else if (isset($object->socid) || isset($object->fk_soc))
			{
				$permissiondellink = $this->canEdit(); // Used by the include of actions_dellink.inc.php
				$action = GETPOST('action', 'alpha');
				$id = GETPOST('id', 'int');

				include DOL_DOCUMENT_ROOT.'/core/actions_dellink.inc.php'; // Must be include, not include_once

				// Show links to link elements
				$linktoelem = $this->form->showLinkToObjectBlock($object);
				$somethingshown = $this->form->showLinkedObjectBlock($object, $linktoelem);
			}

			echo '</div></div>';
		}
	}

	/**
	 * Print documents block
	 *
	 * @param     $object     Object
	 */
	protected function printDocuments($object)
	{
		if (! empty($object) && isset($object->id))
		{
			global $db, $conf, $user;
			
			include_once DOL_DOCUMENT_ROOT . '/core/class/html.formfile.class.php';
			
			$formfile = new FormFile($db);

			echo '<div class="fichecenter"><div class="fichehalfleft">';

			// Documents
			$ref = dol_sanitizeFileName($object->ref);
			$rights_class = get_rights_class();
			$modulepart = get_rights_class(false, true);
			$file = $conf->$modulepart->dir_output . '/' . $ref . '/' . $ref . '.pdf';
			$relativepath = $ref . '/' . $ref . '.pdf';
			$filedir = $conf->$modulepart->dir_output . '/' . $ref;
			$urlsource = $_SERVER["PHP_SELF"] . "?id=" . $object->id;
			$genallowed = $user->rights->$rights_class->create;
			$delallowed = $user->rights->$rights_class->delete;
			if (empty($object->model_pdf)) {
				$const_name    = get_rights_class(true) . '_ADDON_PDF';
				$modelselected = $conf->global->$const_name;
			}
			else {
				$modelselected = $object->model_pdf;
			}
			echo $formfile->showdocuments($modulepart, $ref, $filedir, $urlsource, $genallowed, $delallowed, $modelselected);

			echo '</div></div>';
		}
	}

	/**
	 * Print mail form
	 *
	 * @param     $object       Object
	 * @param     $subject      Mail subject string
	 * @param     $template     Mail template string
	 */
	protected function printMailForm($object, $subject = 'MailSubject', $template = 'MailTemplate')
	{
		if (! empty($object) && isset($object->id))
		{
			global $db, $conf, $langs, $user;

			$rights_class = get_rights_class();

			if (! $this->close_buttons_div) {
				dol_fiche_end();
				$this->close_buttons_div = true;
			}

			echo '<div class="clearboth"></div><br>';
			echo load_fiche_titre($langs->trans('SendByMail'));
			dol_fiche_head();

			// Create mail form
			include_once DOL_DOCUMENT_ROOT . '/core/class/html.formmail.class.php';
			$formmail = new FormMail($db);
			$formmail->param['langsmodels'] = (empty($newlang)?$langs->defaultlang:$newlang);
			$formmail->fromtype = (GETPOST('fromtype')?GETPOST('fromtype'):(!empty($conf->global->MAIN_MAIL_DEFAULT_FROMTYPE)?$conf->global->MAIN_MAIL_DEFAULT_FROMTYPE:'user'));
			if($formmail->fromtype === 'user'){
				$formmail->fromid = $user->id;
				$formmail->frommail = $user->email; // fix for dolibarr 3.9
			}
			$formmail->trackid = $rights_class.$object->id;
			if (! empty($conf->global->MAIN_EMAIL_ADD_TRACK_ID) && ($conf->global->MAIN_EMAIL_ADD_TRACK_ID & 2)) // If bit 2 is set
			{
				include_once DOL_DOCUMENT_ROOT.'/core/lib/functions2.lib.php';
				$formmail->frommail = dolAddEmailTrackId($formmail->frommail, $formmail->trackid);
			}
			$formmail->withfrom = 1;
			$receivers = array();
			if ($object->fetch_thirdparty() > 0)
			{
				foreach ($object->thirdparty->thirdparty_and_contact_email_array(1) as $key => $value) {
					$receivers[$key] = $value;
				}
			}
			$formmail->withto              = GETPOST('sendto') ? GETPOST('sendto') : $receivers;
			$formmail->withtocc            = $receivers;
			$formmail->withtoccc           = $conf->global->MAIN_EMAIL_USECCC;
			$formmail->withtopic           = $langs->trans($subject, '__REF__');
			$formmail->withfile            = 2;
			$formmail->withbody            = $langs->trans($template);
			$formmail->withdeliveryreceipt = 1;
			$formmail->withcancel          = 1;
			// Substitutions Array
			if (method_exists($formmail,"setSubstitFromObject")) { // fix for dolibarr 3.9
				$formmail->setSubstitFromObject($object, $langs);
				$formmail->substit['__CONTACTCIVNAME__'] = '';
				$formmail->substit['__PERSONALIZED__']   = '';
			}
			else {
				$formmail->substit['__CONTACTCIVNAME__'] = '';
				$formmail->substit['__PERSONALIZED__']   = '';
				$formmail->substit['__SIGNATURE__']      = $user->signature;
			}
			$formmail->substit['__REF__'] = $object->ref;

			$custcontact = '';
			$contactarr  = array();
			$contactarr  = $object->liste_contact(-1, 'external');

			if (is_array($contactarr) && count($contactarr) > 0)
			{
				foreach ($contactarr as $contact)
				{
					if ($contact['libelle'] == $langs->trans('TypeContact_external_CUSTOMER')) { // TODO Use code and not label
						$contactstatic = new Contact($db);
						$contactstatic->fetch($contact['id']);
						$custcontact = $contactstatic->getFullName($langs, 1);
					}
				}

				if (! empty($custcontact)) {
					$formmail->substit['__CONTACTCIVNAME__'] = $custcontact;
				}
			}

			// Get file/attachment
			$ref = dol_sanitizeFileName($object->ref);
			include_once DOL_DOCUMENT_ROOT . '/core/lib/files.lib.php';
			$modulepart = get_rights_class(false, true);
			$fileparams = dol_most_recent_file($conf->$modulepart->dir_output . '/' . $ref, preg_quote($ref, '/').'[^\-]+');
			$file = $fileparams['fullname'];

			// Array of additional parameters
			$formmail->param['action']    = 'send';
			$formmail->param['models']    = 'body';
			$formmail->param['models_id'] = GETPOST('modelmailselected', 'int');
			$formmail->param['id']        = $object->id;
			$formmail->param['returnurl'] = $_SERVER["PHP_SELF"] . '?id=' . $object->id;
			$formmail->param['fileinit']  = array($file);

			// Init list of files
			if (GETPOST('mode') == 'init') {
				$formmail->clear_attached_files();
				$formmail->add_attached_files($file, basename($file), dol_mimetype($file));
			}

			// Show form
			echo $formmail->get_form();
		}
	}

	/**
	 * Generate page begining
	 *
	 */
	public function begin()
	{
		global $action;

		// Select mail models is same action as presend
		if (GETPOST('modelselected')) $action = 'presend';

		parent::begin();
	}

	/**
	 * Generate page end
	 *
	 * @param     $object            Object
	 * @param     $mail_subject      Mail subject string
	 * @param     $mail_template     Mail template string
	 */
	public function end($object = '', $mail_subject = 'MailSubject', $mail_template = 'MailTemplate')
	{
		if ($this->close_buttons_div) echo '</div>';

		global $action; // should be global
		$optioncss = GETPOST('optioncss', 'alpha');

		if ($optioncss != 'print')
		{
			if ($action == 'presend') {
				$this->printMailForm($object, $mail_subject, $mail_template);
			}
			else {
				if ($this->show_documents) $this->printDocuments($object);

				$this->printRelatedObjects($object);
			}
		}

		parent::end($this->add_fiche_end);
	}
}