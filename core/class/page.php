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

/**
 * Page class
 */

class Page
{
	/**
	 * @var string Optional head lines
	 */
	protected $head = '';
	/**
	 * @var string Page title
	 */
	protected $title;
	/**
	 * @var string Access permission
	 */
	protected $access_permission = '';
	/**
	 * @var array Page tabs
	 */
	protected $tabs = array();
	/**
	 * @var string Tabs picture
	 */
	protected $tabs_picture = ''; // Leave empty to use the module picture
	/**
	 * @var string Active page tab
	 */
	protected $active_tab = '';
	/**
	 * @var boolean used to close opened form
	 */
	protected $close_form = false;
	/**
	 * @var boolean used to close opened HTML table
	 */
	protected $close_table = false;

	/**
	 * Constructor
	 * 
	 * @param     $page_title     HTML page title
	 * @param     $access_perm    Access permission
	 */
	public function __construct($page_title, $access_perm = '')
	{
		global $langs, $user, $dolibase_config;

		// Set page attributes
		$this->title             = $page_title;
		$this->access_permission = $access_perm;

		// Load translations
		$langs->load($dolibase_config['lang_files'][0]);

		// Access control
		if (! empty($this->access_permission) && ! verifCond($this->access_permission)) {
			accessforbidden();
		}

		// Load default actions
		$this->loadDefaultActions();
	}

	/**
	 * Set page title
	 *
	 * @param     $page_title     Page title
	 */
	public function setTitle($page_title)
	{
		$this->title = $page_title;
	}

	/**
	 * Add js file to page head
	 *
	 * @param     $js_file     Javascript file
	 */
	public function addJsFile($js_file)
	{
		global $dolibase_config;

		$this->head.= '<script type="text/javascript" src="'.DOL_URL_ROOT.$dolibase_config['module_folder'].'/js/'.$js_file.'"></script>'."\n";
	}

	/**
	 * Add css file to page head
	 *
	 * @param     $css_file     CSS file
	 */
	public function addCssFile($css_file)
	{
		global $dolibase_config;

		$this->head.= '<link rel="stylesheet" type="text/css" href="'.DOL_URL_ROOT.$dolibase_config['module_folder'].'/css/'.$css_file.'">'."\n";
	}

	/**
	 * Add a tab to the page
	 *
	 * @param     $title         tab title
	 * @param     $url           tab url
	 * @param     $is_active     should this tab be the activated one (true or false)
	 */
	public function addTab($title, $url, $is_active = false)
	{
		global $langs;

		$tab_id = count($this->tabs) + 1;
		$tab_name = 'tab_'.$tab_id;

		$this->tabs[] = array(
			0 => dol_buildpath($url, 1),
			1 => $langs->trans($title),
			2 => $tab_name
		);

		if ($is_active) {
			$this->active_tab = $tab_name;
		}
	}

	/**
	 * Set tabs picture
	 *
	 * @param     $picture      tabs picture
	 */
	public function setTabsPicture($picture)
	{
		$this->tabs_picture = $picture;
	}

	/**
	 * Generate tabs
	 *
	 */
	protected function generateTabs()
	{
		if (! empty($this->tabs))
		{
			global $conf, $langs, $dolibase_config;

			// Show more tabs from modules
	        // Entries must be declared in modules descriptor with line
	        // $this->tabs = array('entity:+tabname:Title:@mymodule:/mymodule/mypage.php?id=__ID__');   to add new tab
	        // $this->tabs = array('entity:-tabname);   												to remove a tab
	        complete_head_from_modules($conf, $langs, null, $this->tabs, count($this->tabs), $dolibase_config['rights_class']);

	        if (empty($this->tabs_picture)) {
	        	$this->tabs_picture = $dolibase_config['module_picture']."@".$dolibase_config['module_folder'];
	        }

	        // Generate tabs
	        dol_fiche_head($this->tabs, $this->active_tab, $langs->trans($dolibase_config['module_name']), 0, $this->tabs_picture);
	    }
	}

	/**
	 * Add a subtitle
	 *
	 * @param    $title             subtitle title
	 * @param    $picture           subtitle picture
	 * @param    $morehtmlright     more HTML to show on the right
	 */
	public function addSubTitle($title, $picture = 'title_generic.png', $morehtmlright = '')
	{
		global $langs;

		$this->closeTable(); // close last opened table if true

		print load_fiche_titre($langs->trans($title), $morehtmlright, $picture);
	}

	/**
	 * Open a form only if not already opened
	 *
	 * @param     $action     form action
	 */
	public function openForm($action = 'create')
	{
		// i.: HTML form inside another never works, so better not allow it
		if (! $this->close_form)
		{
			print '<form action="' . $_SERVER["PHP_SELF"] . '" method="POST">';
		    print '<input type="hidden" name="token" value="' . $_SESSION ['newtoken'] . '">';
		    print '<input type="hidden" name="action" value="'.$action.'">';

			$this->close_form = true;
		}
	}

	/**
	 * Close an opened form
	 *
	 */
	public function closeForm()
	{
		if ($this->close_form)
		{
			print '</form>';

			$this->close_form = false;
		}

		if (DOLIBASE_ALLOW_FUNC_CHAINING) return $this;
	}

	/**
	 * Opens a new html table
	 *
	 * @param   $header_columns   table header columns, e.: array(
			 												array('name' => 'Column1', 'attr' => 'align="center"'),
			 												array('name' => 'Column2', 'attr' => 'align="center" width="20"')
		 												)
	 * @param   $attr             table attributes
	 * @param   $print_fiche_head print Dolibarr fiche head
	 * @param   $summary          table summary
	 */
	public function openTable($header_columns = array(), $attr = 'class="noborder allwidth"', $print_fiche_head = false, $summary = '')
	{
		global $langs;

		// Close last opened table if true
		$this->closeTable();

		// Print dolibarr fiche head
		if ($print_fiche_head) {
			dol_fiche_head();
		}

		// Print table summary
		if (! empty($summary)) {
			print $langs->trans($summary);
		}

		// Open table
		print '<table '.$attr.'>'."\n";
		if (! empty($header_columns))
		{
			print '<tr class="liste_titre">'."\n";
			foreach ($header_columns as $col) {
				print '<td'.(! empty($col['attr']) ? ' '.$col['attr'] : '').'>'.$langs->trans($col['name']).'</td>'."\n";
			}
			print '</tr>'."\n";
		}

		$this->close_table = true; // a table have been opened & should be closed
	}

	/**
	 * Close an opened html table
	 *
	 * @param   $print_fiche_end   print Dolibarr fiche end
	 */
	public function closeTable($print_fiche_end = false)
	{
		if ($this->close_table)
		{
			// Close table
			print "</table>\n";

			// Print dolibarr fiche end
			if ($print_fiche_end) {
				dol_fiche_end();
			}

			$this->close_table = false;
		}

		if (DOLIBASE_ALLOW_FUNC_CHAINING) return $this;
	}

	/**
	 * Add a line break (or many)
	 *
	 * @param   $repeat   repeat line breaks
	 */
	public function addLineBreak($repeat = 0)
	{
		$repeat = $repeat < 0 ? 0 : $repeat;

		for ($i = 0; $i <= $repeat; $i++) {
			print "<br>\n";
		}
	}

	/**
	 * Load default actions
	 *
	 */
	protected function loadDefaultActions()
	{
		// Put your default actions here
	}

	/**
	 * Generate page begining
	 *
	 */
	public function begin()
	{
		global $langs;
		
		// Load Page Header (Dolibarr header, menus, ...)
		llxHeader($this->head, $langs->trans($this->title));

		// Generate page
		$this->generate();
	}

	/**
	 * Generate page body
	 *
	 */
	protected function generate()
	{
		// Generate tabs
		$this->generateTabs();
	}

	/**
	 * Generate page end
	 *
	 */
	public function end()
	{
		// Page end
		$this->closeTable();
		$this->closeForm();
		if (! empty($this->tabs)) dol_fiche_end();
		llxFooter();
	}
}