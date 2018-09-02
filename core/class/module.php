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
 * Note: each time you add a function in this class/file, it's better to add the same function into your
 * module class also (just copy & paste), because this way you'll avoid old versions non compatibility issues
 * (remember that Dolibase is loaded/included only one time on "dolibarr/admin/modules.php" file),
 * so even if an older Dolibase version is loaded first & this version will probably not include your new function(s), 
 * your module class will always have a copy/override of the function(s) & that's it!
 * (no need to always update your old modules Dolibase version anymore)
 *
 * Another solution would be to use namespaces, to separate each module files, but better not complicate things..
 *
 * P.S: This issue affects only DolibaseModule & Widget class & not the other classes of Dolibase.
 */

include_once DOL_DOCUMENT_ROOT.'/core/modules/DolibarrModules.class.php';

/**
 * DolibaseModule class
 */

if (! class_exists('DolibaseModule')) {

class DolibaseModule extends DolibarrModules
{
	/**
	 * @var array Dolibase module configuration array
	 */
	public $config;
	/**
	 * @var array Dolibase module addons array
	 */
	protected $addons;


	/**
	 * Constructor
	 * 
	 * @param     $db         Database handler
	 */
	public function __construct($db)
	{
		global $dolibase_config, $langs;

		// Check if config array is empty
		if (empty($dolibase_config)) die('Dolibase::Module::Error module configuration not found.');

		// Save module config for further use
		$this->config = $dolibase_config;

		// Load lang files
		$langs->load("module@".$this->config['main']['path']);

		// Module configuration
		$this->db              = $db;
		$this->editor_name     = $this->config['author']['name'];
		$this->editor_url      = $this->config['author']['url'];
		$this->numero          = $this->config['module']['number'];
		$this->rights_class    = $this->config['module']['rights_class'];
		$this->family          = $this->config['module']['family'];
		$this->module_position = $this->config['module']['position'];
		$this->name            = $this->config['module']['name'];
		$this->description     = $this->config['module']['desc'];
		$this->version         = $this->config['module']['version'];
		$this->const_name      = "MAIN_MODULE_".strtoupper($this->name);
		$this->special         = 0;
		$this->picto           = $this->config['module']['picture']."@".$this->config['module']['folder'];

		// Module parts (css, js, ...)
		$this->module_parts    = array();

		// Data directories to create when module is enabled
		$this->dirs            = $this->config['module']['dirs'];

		// Config page
		$this->config_page_url = array($this->config['other']['setup_page']."@".$this->config['module']['folder']);

		// Dependencies
		$this->need_dolibarr_version = $this->config['module']['dolibarr_min'];
		$this->phpmin                = $this->config['module']['php_min'];
		$this->depends               = $this->config['module']['depends'];
		$this->requiredby            = $this->config['module']['required_by'];
		$this->conflictwith          = $this->config['module']['conflict_with'];
		$this->langfiles             = $this->config['other']['lang_files'];

		// Constants
		$this->const = array();

		// Boxes / widgets
		$this->boxes = array();

		// Permissions
		$this->rights = array();

		// Menu
		$this->menu = array();

		// Dictionaries
		$this->dictionaries = array();

		// Addons
		$this->addons = array();

		// Load module settings
		$this->loadSettings();

		// Check for updates
		$this->checkUpdates($langs);
	}

	/**
	 * Function called after module configuration.
	 * 
	 */
	public function loadSettings()
	{
		// add here your module settings
	}

	/**
	 * Function to check module updates.
	 * 
	 * @param     $langs     Language/translation handler
	 */
	protected function checkUpdates($langs)
	{
		if (isset($this->config['module']['check_updates']) && $this->config['module']['check_updates'] && ! empty($this->config['module']['url']) && $this->config['module']['url'] != '#')
		{
			$connected = @fsockopen("www.dolistore.com", 80);

			if ($connected)
			{
				// Close socket
				fclose($connected);

				// Get module page content
				$page = @file_get_contents($this->config['module']['url']);

				// Extract module version
				preg_match("/var module_version = '(.*)'/", $page, $module_version);

				// If a new version is available
				if (isset($module_version[1]) && compare_version($module_version[1], '>', $this->config['module']['version']))
				{
					$this->version .= ' <a href="'.$this->config['module']['url'].'" title="'.$langs->trans('NewVersionAvailable', $module_version[1]).'" target="_blank"><img src="'.dolibase_buildurl('/core/img/update.png').'" class="valignmiddle" width="24" alt="'.$module_version[1].'"></a>';
				}
			}
		}
	}

	/**
	 * Function called when module is enabled.
	 * The init function add constants, boxes, permissions and menus
	 * (defined in constructor) into Dolibarr database.
	 * It also creates data directories
	 *
	 * @param string $options Options when enabling module ('', 'noboxes')
	 * @return int 1 if OK, 0 if KO
	 */
	public function init($options = '')
	{
		//$this->remove($options);

		$sql = array();

		// Load tables
		$result = $this->loadTables();

		// Set addons
		$this->setAddons();

		return $this->_init($sql, $options);
	}

	/**
	 * Set/Activate addons required by module
	 *
	 */
	protected function setAddons()
	{
		foreach ($this->addons as $key => $arr)
		{
			foreach ($arr as $addon)
			{
				$const_prefix = (isset($addon['const_prefix']) && ! empty($addon['const_prefix']) ? $addon['const_prefix'] : get_rights_class(true));

				if ($key == 'doc') {
					$this->addConstant($const_prefix . '_ADDON_PDF', $addon['name']);
					$type = (isset($addon['type']) && ! empty($addon['type']) ? $addon['type'] : get_rights_class());
					addDocumentModel($addon['name'], $type);
				}
				else {
					$this->addConstant($const_prefix . '_ADDON', $addon['name']);
				}
			}
		}
	}

	/**
	 * Create tables, keys and data required by module
	 * Files llx_table1.sql, llx_table1.key.sql llx_data.sql with create table, create keys
	 * and create data commands must be stored in directory /mymodule/sql/
	 * This function is called by this->init
	 *
	 * @return int <=0 if KO, >0 if OK
	 */
	protected function loadTables()
	{
		// Load Dolibase tables
		foreach ($this->config['main']['tables'] as $table) {
			$this->_load_tables($this->config['main']['path'].'/sql/'.$table.'/');
		}

		// Load module tables
		return $this->_load_tables('/'.$this->config['module']['folder'].'/sql/');
	}

	/**
	 * Function called when module is disabled.
	 * Remove from database constants, boxes and permissions from Dolibarr database.
	 * Data directories are not deleted
	 *
	 * @param string $options Options when enabling module ('', 'noboxes')
	 * @return int 1 if OK, 0 if KO
	 */
	public function remove($options = '')
	{
		$sql = array();

		return $this->_remove($sql, $options);
	}

	/**
	 * Add a constant
	 *
	 * @param     $name     constant name
	 * @param     $value    constant value
	 * @param     $desc     constant description / note
	 * @param     $type     constant type
	 */
	public function addConstant($name, $value, $desc = '', $type = 'chaine')
	{
		$this->const[] = array(
			0 => $name,
			1 => $type,
			2 => $value,
			3 => $desc,
			4 => 1, // visiblity
			5 => 'current', // entity 'current' or 'allentities'
			6 => 0 // delete constant when module is disabled
		);
	}

	/**
	 * Add a widget
	 *
	 * @param     $widget_filename           widget filename
	 * @param     $note                      widget note
	 * @param     $enabled_by_default_on     where to enable the widget by default
	 */
	public function addWidget($widget_filename, $note = '', $enabled_by_default_on = 'Home')
	{
		$this->boxes[] = array(
			'file' => $widget_filename.'@'.$this->config['module']['folder'],
			'note' => $note,
			'enabledbydefaulton' => $enabled_by_default_on
		);
	}

	/**
	 * Add a permission
	 *
	 * @param     $name                   permission name
	 * @param     $desc                   permission description
	 * @param     $type                   permission type: 'r', 'c', 'm', 'd'
	 * @param     $enabled_by_default     enable the permission by default for all users
	 */
	public function addPermission($name, $desc = '', $type = '', $enabled_by_default = 1)
	{
		$this->rights[] = array(
			0 => $this->generatePermissionID(), // id
			1 => $desc,
			2 => $type,
			3 => $enabled_by_default,
			4 => $name
		);
	}

	/**
	 * Add a sub permission
	 *
	 * @param     $perm_name              permission name
	 * @param     $subperm_name           sub permission name
	 * @param     $desc                   permission description
	 * @param     $type                   permission type: 'r', 'c', 'm', 'd'
	 * @param     $enabled_by_default     enable the permission by default for all users
	 */
	public function addSubPermission($perm_name, $subperm_name, $desc = '', $type = '', $enabled_by_default = 1)
	{
		/*
		$this->addPermission($perm_name, $desc, $type, $enabled_by_default);
		$last_added = count($this->rights) - 1;
		$this->rights[$last_added][5] = $subperm_name;
		*/
		
		$this->rights[] = array(
			0 => $this->generatePermissionID(), // id
			1 => $desc,
			2 => $type,
			3 => $enabled_by_default,
			4 => $perm_name,
			5 => $subperm_name
		);
	}

	/**
	 * Generate an ID for permissions
	 *
	 * @return     int     permission ID
	 */
	protected function generatePermissionID()
	{
		return (int)$this->config['module']['number'] + count($this->rights) + 1;
	}

	/**
	 * Add a top menu entry
	 *
	 * @param     $name      menu name (should be the same as the module folder name, & the same as the menu picture file *.png)
	 * @param     $title     menu title
	 * @param     $url       target page url
	 * @param     $perms     should anyone see & use the menu or use conditions like '$user->rights->monmodule->level1->level2'
	 * @param     $enabled   should the menu be always enabled or use conditions like '$conf->monmodule->enabled'
	 * @param     $position  menu position
	 * @param     $target    menu target, leave empty or use '_blank' to open in a new window / tab
	 */
	public function addTopMenu($name, $title, $url, $perms = '1', $enabled = '1', $position = 100, $target = '')
	{
		$this->addMenu('top', 0, $name, '', $title, $url, $position, $enabled, $perms, $target);
	}

	/**
	 * Add a left menu entry
	 *
	 * @param     $main_menu    main/top menu name where to insert
	 * @param     $name         menu name (codename for further use)
	 * @param     $title        menu title
	 * @param     $url          target page url
	 * @param     $perms        should anyone see & use the menu or use conditions like '$user->rights->monmodule->level1->level2'
	 * @param     $enabled      should the menu be always enabled or use conditions like '$conf->monmodule->enabled'
	 * @param     $position     menu position
	 * @param     $target       menu target, leave empty or use '_blank' to open in a new window / tab
	 */
	public function addLeftMenu($main_menu, $name, $title, $url, $perms = '1', $enabled = '1', $position = 100, $target = '')
	{
		$this->addMenu('left', 'fk_mainmenu='.$main_menu, $main_menu, $name, $title, $url, $position, $enabled, $perms, $target);
	}

	/**
	 * Add a left sub menu entry
	 *
	 * @param     $main_menu    main/top menu name where to insert
	 * @param     $left_menu    left menu name where to insert
	 * @param     $name         menu name (codename for further use)
	 * @param     $title        menu title
	 * @param     $url          target page url
	 * @param     $perms        should anyone see & use the menu or use conditions like '$user->rights->monmodule->level1->level2'
	 * @param     $enabled      should the menu be always enabled or use conditions like '$conf->monmodule->enabled'
	 * @param     $position     menu position
	 * @param     $target       menu target, leave empty or use '_blank' to open in a new window / tab
	 */
	public function addLeftSubMenu($main_menu, $left_menu, $name, $title, $url, $perms = '1', $enabled = '1', $position = 100, $target = '')
	{
		$this->addMenu('left', 'fk_mainmenu='.$main_menu.',fk_leftmenu='.$left_menu, $main_menu, $name, $title, $url, $position, $enabled, $perms, $target);
	}

	/**
	 * Add a menu
	 *
	 * @param     $type         menu type 'top' or 'left'
	 * @param     $fk_menu      where to insert menu, use 'fk_mainmenu=xxx' or 'fk_mainmenu=xxx,fk_leftmenu=yyy' where xxx is mainmenucode and yyy is a leftmenucode of parent menu
	 * @param     $main_menu    main/top menu name
	 * @param     $left_menu    left menu name
	 * @param     $title        menu title
	 * @param     $url          target page url
	 * @param     $position     menu position
	 * @param     $enabled      define condition to show or hide menu entry. Use '$conf->monmodule->enabled' if entry must be visible if module is enabled.
	 * @param     $perms        use 'perms'=>'$user->rights->monmodule->level1->level2' if you want your menu with a permission rules
	 * @param     $target       menu target, leave empty or use '_blank' to open in a new window / tab
	 */
	protected function addMenu($type, $fk_menu, $main_menu, $left_menu, $title, $url, $position, $enabled = '1', $perms = '1', $target = '')
	{
		$this->menu[] = array(
					'fk_menu' => $fk_menu,
					'type' => $type,
					'titre' => $title,
					'mainmenu' => $main_menu,
					'leftmenu' => $left_menu,
					'url' => $url,
					'langs' => $this->config['other']['menu_lang_file'],
					'position' => $position,
					'enabled' => $enabled,
					'perms' => $perms,
					'target' => $target,
					'user' => 2 // 0=Menu for internal users, 1=external users, 2=both
		);
	}

	/**
	 * Add a CSS file
	 *
	 * @param     $css_filename     css filename
	 */
	public function addCssFile($css_filename)
	{
		$this->addModulePart('css', $this->config['module']['folder'].'/css/'.$css_filename);
	}

	/**
	 * Add a JS file
	 *
	 * @param     $js_filename     javascript filename
	 */
	public function addJsFile($js_filename)
	{
		$this->addModulePart('js', $this->config['module']['folder'].'/js/'.$js_filename);
	}

	/**
	 * Enable a hook
	 *
	 * @param     $hook      dolibarr hook name: 'toprightmenu', 'main', ...
	 */
	public function enableHook($hook)
	{
		$this->addModulePart('hooks', $hook);
	}

	/**
	 * Add a dictionary
	 *
	 * @param     $table_name             table name without prefix
	 * @param     $table_label            table label
	 * @param     $select_fields          select statement fields, e.: 'rowid, code, label, active'
	 * @param     $table_sort             sort field & order, e.: 'label ASC'
	 * @param     $fields_to_show         fields to show on dict page (no spaces), e.: 'code,label'
	 * @param     $fields_to_update       fields to update on dict page (no spaces), e.: 'code,label'
	 * @param     $fields_to_insert       fields to insert on dict page (no spaces), e.: 'code,label'
	 * @param     $table_pk_field         table primary key field
	 * @param     $fields_help            fields help summary or link, e.: array('code' => 'summary..', 'label' => 'summary..')
	 */
	public function addDictionary($table_name, $table_label, $select_fields = 'rowid, label, active', $table_sort = 'label ASC', $fields_to_show = 'label', $fields_to_update = 'label', $fields_to_insert = 'label', $table_pk_field = 'rowid', $fields_help = array())
	{
		global $conf;

		$dict_table = MAIN_DB_PREFIX.$table_name;
		$modulepart = get_rights_class(false, true);

		if (! isset($this->dictionaries['langs'])) {
			$this->dictionaries['langs'] = $this->config['other']['lang_files'][0];
		}

		$this->dictionaries['tabname'][]        = $dict_table;
		$this->dictionaries['tablib'][]         = $table_label;
		$this->dictionaries['tabsql'][]         = 'SELECT '.$select_fields.' FROM '.$dict_table;
		$this->dictionaries['tabsqlsort'][]     = $table_sort;
		$this->dictionaries['tabfield'][]       = $fields_to_show;
		$this->dictionaries['tabfieldvalue'][]  = $fields_to_update;
		$this->dictionaries['tabfieldinsert'][] = $fields_to_insert;
		$this->dictionaries['tabrowid'][]       = $table_pk_field;
		$this->dictionaries['tabcond'][]        = $conf->$modulepart->enabled;
		$this->dictionaries['tabhelp'][]        = $fields_help;
	}

	/**
	 * Add a module part
	 *
	 * @param     $module_part     module part: 'hooks', 'css', 'js'
	 * @param     $value           module part value (could be a css or js filenames, a hook, etc)
	 */
	protected function addModulePart($module_part, $value)
	{
		$this->module_parts[$module_part][] = $value;
	}

	/**
	 * Activate a numbering model
	 *
	 * @param     $name             numbering model name
	 * @param     $const_prefix     numbering model constant prefix
	 */
	public function activateNumModel($name, $const_prefix = '')
	{
		$this->addons['num'][] = array('name' => $name, 'const_prefix' => $const_prefix);
	}

	/**
	 * Activate a document model
	 *
	 * @param     $name             document model name
	 * @param     $type             document model type
	 * @param     $const_prefix     document model constant prefix
	 */
	public function activateDocModel($name, $type = '', $const_prefix = '')
	{
		$this->addons['doc'][] = array('name' => $name, 'type' => $type, 'const_prefix' => $const_prefix);
	}
}

}