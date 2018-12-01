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
	 * @var array Page main subtitle settings
	 */
	protected $main_subtitle = array();
	/**
	 * @var string Tabs picture
	 */
	protected $tabs_picture = ''; // Leave empty to use the module picture
	/**
	 * @var string Tabs title
	 */
	protected $tabs_title = ''; // Leave empty to use the module name
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
	 * @var boolean used to add fiche end
	 */
	protected $add_fiche_end = true;
	/**
	 * @var string Module rights class
	 */
	protected $rights_class;
	/**
	 * @var string Module part
	 */
	protected $modulepart;
	/**
	 * @var array Page assets
	 */
	protected $assets = array(
		'css' => array(),
		'js'  => array()
	);


	/**
	 * Constructor
	 * 
	 * @param     $page_title     HTML page title
	 * @param     $access_perm    Access permission
	 */
	public function __construct($page_title, $access_perm = '')
	{
		global $langs, $user, $dolibase_config;

		// Start measuring time after construct call
		start_time_measure('after_construct_call', __METHOD__);

		// Set page attributes
		$this->title             = $page_title;
		$this->access_permission = $access_perm;
		$this->rights_class      = get_rights_class();
		$this->modulepart        = get_modulepart();

		// Load translations
		$langs->load($dolibase_config['other']['lang_files'][0]);

		// Access control
		if (! empty($this->access_permission) && ! verifCond($this->access_permission)) {
			accessforbidden();
		}

		// Add some custom css
		$this->appendToHead('<link rel="stylesheet" type="text/css" href="'.dolibase_buildurl('/core/css/page.css.php').'">'."\n");

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
	 * Set page main subtitle
	 *
	 * @param     $title     title
	 * @param     $picture   subtitle picture
	 */
	public function setMainSubtitle($title, $picture = 'title_generic.png')
	{
		$this->main_subtitle = array('title' => $title, 'picture' => $picture);
	}

	/**
	 * Load a language file
	 *
	 * @param     $lang_file          Language file
	 * @param     $is_module_file     File is in module 'langs' directory or not
	 */
	public function loadLang($lang_file, $is_module_file = true)
	{
		global $langs, $dolibase_config;

		if ($is_module_file) {
			$lang_file = $lang_file.'@'.$dolibase_config['module']['folder'];
		}

		$langs->load($lang_file);
	}

	/**
	 * Load an array of language files
	 *
	 * @param     $lang_files_array   Language files array
	 * @param     $from_module        Files should be loaded from module 'langs' directory or not
	 */
	public function loadLangs($lang_files_array, $from_module = false)
	{
		foreach ($lang_files_array as $lang_file) {
			$this->loadLang($lang_file, $from_module);
		}
	}

	/**
	 * Append content to page head
	 *
	 * @param     $content     content to add
	 */
	public function appendToHead($content)
	{
		$this->head.= $content;
	}

	/**
	 * Add JS file to page head
	 *
	 * @param     $js_file     Javascript file
	 */
	public function addJsFile($js_file)
	{
		global $dolibase_config;

		//$this->appendToHead('<script type="text/javascript" src="'.dol_buildpath($dolibase_config['module']['folder'].'/js/'.$js_file, 1).'"></script>'."\n");

		$this->assets['js'][] = $dolibase_config['module']['folder'].'/js/'.$js_file;
	}

	/**
	 * Add an array of JS files
	 *
	 * @param     $js_files_array     Javascript files array
	 */
	public function addJsFiles($js_files_array)
	{
		foreach ($js_files_array as $js_file) {
			$this->addJsFile($js_file);
		}
	}

	/**
	 * Add CSS file to page head
	 *
	 * @param     $css_file     CSS file
	 */
	public function addCssFile($css_file)
	{
		global $dolibase_config;

		//$this->appendToHead('<link rel="stylesheet" type="text/css" href="'.dol_buildpath($dolibase_config['module']['folder'].'/css/'.$css_file, 1).'">'."\n");

		$this->assets['css'][] = $dolibase_config['module']['folder'].'/css/'.$css_file;
	}

	/**
	 * Add an array of CSS files
	 *
	 * @param     $css_files_array     CSS files array
	 */
	public function addCssFiles($css_files_array)
	{
		foreach ($css_files_array as $css_file) {
			$this->addCssFile($css_file);
		}
	}

	/**
	 * Add a tab to the page
	 * Note: this function should be called before $page->begin() function, otherwise it will not work as expected.
	 *
	 * @param     $title         tab title
	 * @param     $url           tab url
	 * @param     $is_active     should this tab be the activated one (true or false)
	 * @param     $position      tab position (-1 means add to the end)
	 */
	public function addTab($title, $url, $is_active = false, $position = -1)
	{
		global $langs;

		$tab_id = count($this->tabs) + 1;
		$tab_name = 'tab_'.$tab_id;

		$tab = array(
			0 => dol_buildpath($url, 1),
			1 => $langs->trans($title),
			2 => $tab_name
		);

		if ($position >= 0) {
			array_splice($this->tabs, $position, 0, $tab);
		}
		else {
			$this->tabs[] = $tab;
		}

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
	 * Set tabs title
	 *
	 * @param     $title      tabs title
	 */
	public function setTabsTitle($title)
	{
		global $langs;

		$this->tabs_title = $langs->trans($title);
	}

	/**
	 * Generate tabs
	 *
	 * @param     $noheader     -1 or 0=Add tab header, 1=no tab header.
	 */
	protected function generateTabs($noheader = 0)
	{
		if (! empty($this->tabs))
		{
			global $conf, $langs, $dolibase_config;

			// Show more tabs from modules
			// Entries must be declared in modules descriptor with line
			// $this->tabs = array('entity:+tabname:Title:@mymodule:/mymodule/mypage.php?id=__ID__');   to add new tab
			// $this->tabs = array('entity:-tabname);   												to remove a tab
			complete_head_from_modules($conf, $langs, null, $this->tabs, count($this->tabs), $this->rights_class);

			if (empty($this->tabs_picture)) {
				$this->tabs_picture = $dolibase_config['module']['picture']."@".$dolibase_config['module']['folder'];
			}

			if (empty($this->tabs_title)) {
				$this->tabs_title = $langs->trans($dolibase_config['module']['name']);
			}

			// Generate tabs
			dol_fiche_head($this->tabs, $this->active_tab, $this->tabs_title, $noheader, $this->tabs_picture);
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

		echo load_fiche_titre($langs->trans($title), $morehtmlright, $picture);
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
			echo '<form action="' . $_SERVER["PHP_SELF"] . '" method="POST">';
			echo '<input type="hidden" name="token" value="' . $_SESSION ['newtoken'] . '">';
			echo '<input type="hidden" name="action" value="'.$action.'">';

			$this->close_form = true;
		}
	}

	/**
	 * Close an opened form
	 *
	 * @return  $this
	 */
	public function closeForm()
	{
		if ($this->close_form)
		{
			echo '</form>';

			$this->close_form = false;
		}

		return $this;
	}

	/**
	 * Opens a new html table
	 *
	 * @param   $header_columns   table header columns, e.: array(
	 *                                                          array('name' => 'Column1', 'attr' => 'align="center"'),
	 *                                                          array('name' => 'Column2', 'attr' => 'align="center" width="20"')
	 *                                                      )
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
			echo $langs->trans($summary);
		}

		// Open table
		echo '<table '.$attr.'>'."\n";
		if (! empty($header_columns))
		{
			echo '<tr class="liste_titre">'."\n";
			foreach ($header_columns as $col) {
				echo '<td'.(isset($col['attr']) && ! empty($col['attr']) ? ' '.$col['attr'] : '').'>'.$langs->trans($col['name']).'</td>'."\n";
			}
			echo '</tr>'."\n";
		}

		$this->close_table = true; // a table have been opened & should be closed
	}

	/**
	 * Close an opened html table
	 *
	 * @param   $print_fiche_end   print Dolibarr fiche end
	 * @return  $this
	 */
	public function closeTable($print_fiche_end = false)
	{
		if ($this->close_table)
		{
			// Close table
			echo "</table>\n";

			// Print dolibarr fiche end
			if ($print_fiche_end) {
				dol_fiche_end();
			}

			$this->close_table = false;
		}

		return $this;
	}

	/**
	 * Open a table row
	 *
	 * @param   $odd         row is odd or peer
	 * @param   $more_attr   more attributes to add
	 */
	public function openRow($odd = true, $more_attr = '')
	{
		global $bc;

		echo '<tr '.$bc[$odd].(! empty($more_attr) ? ' '.$more_attr : '').'>';
	}

	/**
	 * Close a table row
	 *
	 */
	public function closeRow()
	{
		echo '</tr>';
	}

	/**
	 * Add a table column
	 *
	 * @param   $content   column content
	 * @param   $attr      column attributes
	 */
	public function addColumn($content, $attr = '')
	{
		echo '<td'.(! empty($attr) ? ' '.$attr : '').'>'.$content.'</td>';
	}

	/**
	 * Add a line break (or many)
	 *
	 * @param   $repeat   repeat line breaks
	 */
	public function addLineBreak($repeat = 0)
	{
		if ($repeat < 0) {
			$repeat = 0;
		}

		for ($i = 0; $i <= $repeat; $i++) {
			echo "<br>\n";
		}
	}

	/**
	 * Return template absolute path
	 *
	 * @param   $template      template relative path or name
	 * @return  string         template absolute path
	 */
	protected function getTemplatePath($template)
	{
		global $dolibase_config;

		$path = preg_replace('/^\//', '', $template); // Clean the path

		return dol_buildpath($dolibase_config['module']['folder'].'/tpl/'.$path);
	}

	/**
	 * Include a template into the page.
	 * Note: the template should be inside module tpl folder when $path_is_absolute parameter equal false.
	 *
	 * @param   $template_path      template path
	 * @param   $path_is_absolute   define whether the template path is absolute or not
	 * @param   $use_require_once   permit to avoid including the template many times on the same page
	 * @param   $template_params    template parameters
	 */
	public function showTemplate($template_path, $path_is_absolute = false, $use_require_once = false, $template_params = array())
	{
		// Stop measuring time after begin call & Start measuring time after showTemplate call
		start_time_measure('after_showTemplate_call', __METHOD__, 'after_begin_call');

		$path = $path_is_absolute ? $template_path : $this->getTemplatePath($template_path);

		foreach ($template_params as $param => $value) {
			${$param} = $value;
		}

		if ($use_require_once) {
			require_once $path;
		} else {
			require $path;
		}

		stop_time_measure('after_showTemplate_call');
	}

	/**
	 * Show page_under_construction template (only once)
	 *
	 */
	public function underConstruction()
	{
		$template_path = dolibase_buildpath('/core/tpl/page_under_construction.php');

		$this->showTemplate($template_path, true, true);
	}

	/**
	 * Show page_not_found template (only once)
	 *
	 */
	public function notFound()
	{
		$template_path = dolibase_buildpath('/core/tpl/page_not_found.php');

		$this->showTemplate($template_path, true, true);
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
	 * Generate page beginning
	 *
	 * @return  $this
	 */
	public function begin()
	{
		global $langs;

		// Stop measuring time after construct call & Start measuring time after begin
		start_time_measure('after_begin_call', __METHOD__, 'after_construct_call');

		// Load Page Header (Dolibarr header, menus, ...)
		llxHeader($this->head, $langs->trans($this->title), '', '', 0, 0, $this->assets['js'], $this->assets['css']);

		// Generate page
		$this->generate();

		return $this;
	}

	/**
	 * Generate page body
	 *
	 */
	protected function generate()
	{
		// Add main subtitle
		if (! empty($this->main_subtitle)) {
			$this->addSubTitle($this->main_subtitle['title'], $this->main_subtitle['picture']);
		}

		// Generate tabs
		$this->generateTabs();
	}

	/**
	 * Redirect to a url (alias for dolibase_redirect function)
	 *
	 */
	public function redirect($url)
	{
		dolibase_redirect($url);
	}

	/**
	 * Generate page end
	 *
	 */
	public function end()
	{
		global $db;

		// Stop measuring time after begin call & Start measuring time after end ('after_end_call' will be stopped when rendering the Debug bar)
		start_time_measure('after_end_call', __METHOD__, 'after_begin_call');

		// Page end
		$this->closeTable();
		$this->closeForm();
		if (! empty($this->tabs) && $this->add_fiche_end) dol_fiche_end();
		llxFooter();
		$db->close();
	}
}
