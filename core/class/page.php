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
	 * @var boolean Page is admin only
	 */
	protected $admin_only = false;
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
	 * Constructor
	 * 
	 * @param     $page_title     HTML page title
	 * @param     $admin_only     page is admin only or not
	 */
	public function __construct($page_title, $admin_only = false)
	{
		// Set page attributes
		$this->title = $page_title;
		$this->admin_only = $admin_only;
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

		print load_fiche_titre($langs->trans($title), $morehtmlright, $picture);
	}

	/**
	 * Generate page begining
	 *
	 */
	public function begin()
	{
		global $langs, $user, $dolibase_config;

		// Load translations
		$langs->load($dolibase_config['lang_files'][0]);

		// Access control
		if ($this->admin_only && ! $user->admin) {
			accessforbidden();
		}

		// Load default actions
		$this->loadDefaultActions();

		// Page Header (Dolibarr header, menus, ...)
		llxHeader($this->head, $langs->trans($this->title));

		// Generate page
		$this->generate();
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
		if (! empty($this->tabs)) dol_fiche_end();
		llxFooter();
	}
}