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
include_once DOL_DOCUMENT_ROOT . '/core/class/extrafields.class.php';

/**
 * ListPage class
 */

class ListPage extends FormPage
{
	/**
	 * @var array Fields to show or not (used in openList function)
	 */
	protected $arrayfields = array();
	/**
	 * @var string Page context
	 */
	protected $contextpage = '';
	/**
	 * @var object extrafields object
	 */
	protected $extrafields;
	/**
	 * @var array extrafields labels
	 */
	protected $extralabels = array();
	/**
	 * @var array extrafields search options
	 */
	protected $search_array_options = array();


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
	 * Load default actions
	 *
	 */
	protected function loadDefaultActions()
	{
		global $hookmanager;

		$this->contextpage = get_rights_class(false, true) . 'list';

		// Initialize technical object to manage hooks of thirdparties.
		$hookmanager->initHooks(array($this->contextpage));

		// Selection of new fields
		global $db, $conf, $user, $contextpage;
		$contextpage = $this->contextpage;
		include DOL_DOCUMENT_ROOT.'/core/actions_changeselectedfields.inc.php';

		// Purge search criteria
		if (GETPOST("button_removefilter_x") || GETPOST("button_removefilter")) // Both test are required to be compatible with all browsers
		{
			$_POST = array();
			$_GET  = array();
		}
	}

	/**
	 * Open list / print list head
	 *
	 * @param   $title               List title
	 * @param   $picture             List picture
	 * @param   $list_fields         List fields
	 * @param   $search_fields       List search fields
	 * @param   $nbofshownrecords    Number of shown records
	 * @param   $nbtotalofrecords    Total number of records
	 * @param   $fieldstosearchall   Fields to search all
	 * @param   $sortfield           Sort field
	 * @param   $sortorder           Sort order
	 */
	public function openList($title, $picture = 'title_generic.png', $list_fields, $search_fields, $nbofshownrecords, $nbtotalofrecords, $fieldstosearchall = array(), $sortfield = '', $sortorder = '')
	{
		global $langs, $conf;

		// Get parameters
		$contextpage = GETPOST('contextpage','aZ') ? GETPOST('contextpage','aZ') : $this->contextpage;
		$sall = isset($search_fields['all']) ? $search_fields['all'] : GETPOST('sall', 'alphanohtml');
		$page = GETPOST('page', 'int') ? GETPOST('page', 'int') : 0;
		if (empty($sortorder)) $sortorder = GETPOST('sortorder', 'alpha');
		if (empty($sortfield)) $sortfield = GETPOST('sortfield', 'alpha');
		$limit = GETPOST('limit') ? GETPOST('limit', 'int') : $conf->liste_limit;
		$optioncss = GETPOST('optioncss', 'alpha');

		// List form
		echo '<form action="'.$_SERVER["PHP_SELF"].'" method="POST">';
		echo '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
		echo '<input type="hidden" name="formfilteraction" id="formfilteraction" value="list">';
		echo '<input type="hidden" name="action" value="list">';
		echo '<input type="hidden" name="sortfield" value="'.$sortfield.'">';
		echo '<input type="hidden" name="sortorder" value="'.$sortorder.'">';

		// Add list parameters
		$param = '';
		foreach ($search_fields as $key => $value) {
			if ($value != '') $param.= '&'.$key.'='.urlencode($value);
		}
		if (! empty($contextpage) && $contextpage != $_SERVER["PHP_SELF"]) $param.= '&contextpage='.urlencode($contextpage);
		if ($limit > 0 && $limit != $conf->liste_limit) $param.= '&limit='.urlencode($limit);
		if ($optioncss != '') $param.= '&optioncss='.urlencode($optioncss);
		// Add $param from extra fields
		$search_array_options = $this->search_array_options;
		include_once DOL_DOCUMENT_ROOT.'/core/tpl/extrafields_list_search_param.tpl.php';

		// List title
		$title = $langs->trans($title);
		print_barre_liste($title, $page, $_SERVER["PHP_SELF"], $param, $sortfield, $sortorder, '', $nbofshownrecords, $nbtotalofrecords, $picture, 0, '', '', $limit);

		if ($sall)
		{
			foreach($fieldstosearchall as $key => $val) {
				$fieldstosearchall[$key] = $langs->trans($val);
			}
			echo $langs->trans("FilterOnInto", $sall) . join(', ',$fieldstosearchall);
		}

		echo '<div class="div-table-responsive">';
		echo '<table class="tagtable liste">'."\n";

		// Generate $arrayfields
		$this->arrayfields = array();
		foreach ($list_fields as $field) {
			$checked = (isset($field['checked']) ? $field['checked'] : 1);
			$enabled = (isset($field['enabled']) ? verifCond($field['enabled']) : 1);
			$this->arrayfields[$field['name']] = array('label' => $field['label'], 'checked' => $checked, 'enabled' => $enabled);
		}
		// Extra fields
		if (is_array($this->extrafields->attribute_label) && count($this->extrafields->attribute_label))
		{
			foreach($this->extrafields->attribute_label as $key => $val)
			{
				if (! empty($this->extrafields->attribute_list[$key])) {
					$this->arrayfields['ef.'.$key] = array('label'=>$this->extrafields->attribute_label[$key], 'checked'=>(($this->extrafields->attribute_list[$key]<0)?0:1), 'position'=>$this->extrafields->attribute_pos[$key], 'enabled'=>(abs($this->extrafields->attribute_list[$key])!=3 && $this->extrafields->attribute_perms[$key]));
				}
			}
		}
		// This change content of $arrayfields
		$varpage = empty($contextpage) ? $_SERVER["PHP_SELF"] : $contextpage;
		$selectedfields = $this->form->multiSelectArrayWithCheckbox('selectedfields', $this->arrayfields, $varpage);

		// List fields
		echo '<tr class="liste_titre">';
		foreach ($list_fields as $field) {
			if (! empty($this->arrayfields[$field['name']]['checked'])) {
				$field_align = (isset($field['align']) ? 'align="'.$field['align'].'"' : '');
				$field_class = (isset($field['class']) ? $field['class'].' ' : '');
				print_liste_field_titre($langs->trans($field['label']), $_SERVER["PHP_SELF"], $field['name'], '', $param, $field_align, $sortfield, $sortorder, $field_class);
			}
		}
		// Extra fields
		$extrafields = $this->extrafields;
		$extralabels = $this->extralabels;
		$arrayfields = $this->arrayfields;
		include_once DOL_DOCUMENT_ROOT.'/core/tpl/extrafields_list_search_title.tpl.php';
		print_liste_field_titre($selectedfields, $_SERVER["PHP_SELF"], '', '', '', 'align="right"', $sortfield, $sortorder, 'maxwidthsearch ');
		echo "</tr>\n";

		// List search fields
		if ($optioncss != 'print')
		{
			echo '<tr class="liste_titre liste_titre_filter">';
			foreach ($list_fields as $field) {
				if (! empty($this->arrayfields[$field['name']]['checked'])) {
					$field_align = (isset($field['align']) ? ' align="'.$field['align'].'"' : '');
					$field_class = (isset($field['class']) ? ' '.$field['class'] : '');
					$search_input = (isset($field['search_input']) ? $field['search_input'] : '');
					echo '<td class="liste_titre'.$field_class.'"'.$field_align.'>'.$search_input.'</td>';
				}
			}
			// Extra fields
			include_once DOL_DOCUMENT_ROOT.'/core/tpl/extrafields_list_search_input.tpl.php';
			// search buttons
			echo '<td class="liste_titre" align="right"><input type="image" class="liste_titre" name="button_search" src="'.img_picto($langs->trans("Search"),'search.png','','',1).'" value="'.dol_escape_htmltag($langs->trans("Search")).'" title="'.dol_escape_htmltag($langs->trans("Search")).'">';
			echo '<input type="image" class="liste_titre" name="button_removefilter" src="'.img_picto($langs->trans("Search"),'searchclear.png','','',1).'" value="'.dol_escape_htmltag($langs->trans("RemoveFilter")).'" title="'.dol_escape_htmltag($langs->trans("RemoveFilter")).'">';
			echo "</td></tr>\n";
		}
	}

	/**
	 * Add a table column
	 *
	 * @param   $field_name   field name
	 * @param   $content      column content
	 * @param   $attr         column attributes
	 */
	public function addColumn($field_name, $content, $attr = '')
	{
		if (! empty($this->arrayfields[$field_name]['checked'])) {
			parent::addColumn($content, $attr);
		}
	}

	/**
	 * Add extrafields columns
	 *
	 * @param   $obj   object
	 */
	public function addExtraFields($obj)
	{
		global $db, $conf;

		$extrafields = $this->extrafields;
		$arrayfields = $this->arrayfields;

		include DOL_DOCUMENT_ROOT.'/core/tpl/extrafields_list_print_fields.tpl.php';
	}

	/**
	 * Fetch extrafields
	 *
	 * @param   $obj   object
	 */
	public function fetchExtraFields($elementtype, &$more_fields, &$join, &$where)
	{
		global $db, $conf;

		// fetch optionals attributes and labels
		$this->extralabels = $this->extrafields->fetch_name_optionals_label($elementtype);
		$this->search_array_options = $this->extrafields->getOptionalsFromPost($this->extralabels, '', 'search_');

		// Add fields from extrafields
		foreach ($this->extrafields->attribute_label as $key => $val) {
			$more_fields.= ($extrafields->attribute_type[$key] != 'separate' ? ', ef.'.$key.' as options_'.$key : '');
		}
		if (is_array($this->extrafields->attribute_label) && count($this->extrafields->attribute_label)) {
			$join.= " LEFT JOIN ".MAIN_DB_PREFIX.$elementtype."_extrafields as ef on (t.rowid = ef.fk_object)";
		}

		// Add where from extra fields
		$sql = '';
		$extrafields = $this->extrafields;
		$search_array_options = $this->search_array_options;
		// Fix date search
		foreach ($search_array_options as $key => $val)
		{
			$tmpkey = preg_replace('/search_options_/', '', $key);
			$type = $extrafields->attribute_type[$tmpkey];
			if (in_array($type, array('date', 'datetime')) && ! empty($val)) {
				$where.= " AND date(ef.".$tmpkey.") = date('".$db->idate($val)."')";
				unset($search_array_options[$key]);
			}
		}
		include_once DOL_DOCUMENT_ROOT.'/core/tpl/extrafields_list_search_sql.tpl.php';
		$where.= $sql;
	}

	/**
	 * Add buttons to the list
	 *
	 * @param   $buttons        buttons to add
	 * @param   $hide_buttons   hide buttons by default
	 */
	protected function addButtons($buttons, $hide_buttons)
	{
		global $langs;

		echo '<div class="tabsAction'.($hide_buttons ? ' hidden' : '').'">';

		foreach ($buttons as $button)
		{
			if (! isset($button['enabled']) || verifCond($button['enabled'])) {
				echo '<input type="submit" class="butAction" name="'.$button['name'].'" value="'.$langs->trans($button['label']).'">';
			}
		}

		echo '</div>';
	}

	/**
	 * Close list
	 *
	 * @param   $buttons        buttons to add before close list
	 * @param   $hide_buttons   hide buttons by default
	 */
	public function closeList($buttons = array(), $hide_buttons = false)
	{
		echo "</table>\n";

		$optioncss = GETPOST('optioncss', 'alpha');
		
		if (! empty($buttons) && $optioncss != 'print') $this->addButtons($buttons, $hide_buttons);

		echo "</div></form>\n";
	}

	/**
	 * Close table row
	 *
	 */
	public function closeRow()
	{
		echo '<td></td>';

		parent::closeRow();
	}
}