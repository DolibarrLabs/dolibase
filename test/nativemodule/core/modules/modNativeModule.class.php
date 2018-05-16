<?php

// Load Dolibase config file for this module (mandatory)
include_once dirname(__FILE__) . '/../../config.php'; // we use dirname(__FILE__) because this file is included by Dolibarr admin/modules.php file
//dol_include_once('/myfirstmodule/config.php'); // may work also
include_once DOL_DOCUMENT_ROOT.'/core/modules/DolibarrModules.class.php';

/**
 *	Class to describe and enable module
 */
class modNativeModule extends DolibarrModules
{
	/**
	 * @var array Dolibase module configuration array
	 */
	public $config;


	/**
	 * Constructor
	 * 
	 * @param     $db         Database handler
	 */
	public function __construct($db)
	{
		// set module configuration
		$this->setConfig($db);

		// add options/settings
		$this->addConstant("NATIVE_MODULE_CONST", "test");
		$this->addConstant("NATIVE_MODULE_SECOND_CONST", "test2");

		$this->addWidget("nativebox.php");

		$this->addCssFile("mycss.css.php");
		//$this->addCssFile("mycss2.css.php");

		$this->addPermission("use", "UseTheModule");
		//$this->addPermission("read", "Read permission", "r");
		//$this->addPermission("create", "Create permission", "c");
		//$this->addPermission("modify", "Modify permission", "m");
		//$this->addPermission("delete", "Delete permission", "d");

		//$this->addSubPermission("delete", "all", "Delete all permissions", "d");

		$this->addTopMenu($this->config['top_menu_name'], "MyFirstMenu", "/myfirstmodule/index.php?test=1");

		//$this->addTopMenu("mysecondmenu", "MySecondMenu", "/myfirstmodule/index.php?test=10");

		$this->addLeftMenu($this->config['top_menu_name'], "myleftmenu", "MyLeftMenu", "/myfirstmodule/index.php?test=2");

		$this->addLeftSubMenu($this->config['top_menu_name'], "myleftmenu", "mysubleftmenu", "MySubLeftMenu", "/myfirstmodule/index.php?test=3");

		$this->addLeftSubMenu($this->config['top_menu_name'], "mysubleftmenu", "", "MySecondSubLeftMenu", "/myfirstmodule/index.php?test=4");

		$this->addLeftMenu($this->config['top_menu_name'], "mysecondleftmenu", "MySecondLeftMenu", "/myfirstmodule/index.php?test=5");

		// Exports
		//--------
	}

	/**
	 * Set module configuration
	 * 
	 * @param     $db         Database handler
	 */
	protected function setConfig($db)
	{
		global $dolibase_config;

		// Check if config array is empty
		if (empty($dolibase_config)) die('Dolibase::Module::Error module configuration not found.');

		// Save module config for further use
		$this->config = $dolibase_config;

		// Module configuration
		$this->db              = $db;
		$this->editor_name     = $this->config['editor_name'];
		$this->editor_url      = $this->config['editor_url'];
		$this->numero          = $this->config['module_number'];
		$this->rights_class    = $this->config['rights_class'];
		$this->family          = $this->config['module_family'];
		$this->module_position = $this->config['module_position'];
		$this->name            = $this->config['module_name'];
		$this->description     = $this->config['module_desc'];
		$this->version         = $this->config['module_version'];
		$this->const_name      = "MAIN_MODULE_".strtoupper($this->name);
		$this->special         = 0;
		$this->picto           = $this->config['module_picture']."@".$this->config['module_folder'];

		// Module parts (css, js, ...)
		$this->module_parts    = array();

		// Data directories to create when module is enabled
		$this->dirs            = $this->config['module_dirs'];

		// Config page
		$this->config_page_url = array($this->config['setup_page_url']."@".$this->config['module_folder']);

		// Dependencies
		$this->need_dolibarr_version = $this->config['need_dolibarr'];
		$this->phpmin                = $this->config['need_php'];
		$this->depends               = $this->config['module_depends'];
		$this->requiredby            = $this->config['required_by'];
		$this->conflictwith          = $this->config['conflict_with'];
		$this->langfiles             = $this->config['lang_files'];

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

		$result = $this->loadTables();

		return $this->_init($sql, $options);
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
		$dolibase_path = (DOLIBASE_PATH == '/dolibase' ? DOLIBASE_PATH : '/'.$this->config['module_folder'].'/dolibase');
		
		if (DOLIBASE_ENABLE_LOGS) {
			$this->_load_tables($dolibase_path.'/sql/logs/');
		}
		
		// Load module tables
		return $this->_load_tables('/'.$this->config['module_folder'].'/sql/');
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
			'file' => $widget_filename.'@'.$this->config['module_folder'],
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
	private function generatePermissionID()
	{
		return (int)$this->config['module_number'] + count($this->rights) + 1;
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
	private function addMenu($type, $fk_menu, $main_menu, $left_menu, $title, $url, $position, $enabled = '1', $perms = '1', $target = '')
	{
		$this->menu[] = array(
					'fk_menu' => $fk_menu,
					'type' => $type,
					'titre' => $title,
					'mainmenu' => $main_menu,
					'leftmenu' => $left_menu,
					'url' => $url,
					'langs' => $this->config['menu_lang_file'],
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
		$this->addModulePart('css', $this->config['module_folder'].'/css/'.$css_filename);
	}

	/**
	 * Add a JS file
	 *
	 * @param     $js_filename     javascript filename
	 */
	public function addJsFile($js_filename)
	{
		$this->addModulePart('js', $this->config['module_folder'].'/js/'.$js_filename);
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

		$dict_table   = MAIN_DB_PREFIX.$table_name;
		$rights_class = $this->config['rights_class'];

		if (! isset($this->dictionaries['langs'])) {
			$this->dictionaries['langs'] = $this->config['lang_files'][0];
		}

		$this->dictionaries['tabname'][]        = $dict_table;
		$this->dictionaries['tablib'][]         = $table_label;
		$this->dictionaries['tabsql'][]         = 'SELECT '.$select_fields.' FROM '.$dict_table;
		$this->dictionaries['tabsqlsort'][]     = $table_sort;
		$this->dictionaries['tabfield'][]       = $fields_to_show;
		$this->dictionaries['tabfieldvalue'][]  = $fields_to_update;
		$this->dictionaries['tabfieldinsert'][] = $fields_to_insert;
		$this->dictionaries['tabrowid'][]       = $table_pk_field;
		$this->dictionaries['tabcond'][]        = $conf->$rights_class->enabled;
		$this->dictionaries['tabhelp'][]        = $fields_help;
	}

	/**
	 * Add a module part
	 *
	 * @param     $module_part     module part: 'hooks', 'css', 'js'
	 * @param     $value           module part value (could be a css or js filenames, a hook, etc)
	 */
	private function addModulePart($module_part, $value)
	{
		$this->module_parts[$module_part][] = $value;
	}
}
