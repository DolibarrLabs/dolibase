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
	 * Constructor
	 * 
	 * @param     $page_title     HTML page title
	 * @param     $access_perm    Access permission
	 */
	public function __construct($page_title, $access_perm = '')
	{
		parent::__construct($page_title, $access_perm);
	}

	/**
	 * Load default actions
	 *
	 */
	protected function loadDefaultActions()
	{
		global $hookmanager, $dolibase_config;

		$hook_name = strtolower($dolibase_config['module']['rights_class']).'list';
		$this->contextpage = str_replace('_', '', $hook_name);

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