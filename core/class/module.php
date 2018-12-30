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

require_once DOL_DOCUMENT_ROOT . '/core/modules/DolibarrModules.class.php';

if (! class_exists('DolibaseModule')) {

/**
 * DolibaseModule class
 *
 * Known issue: When Dolibase is installed globally, DolibaseModule class will be loaded/included only once
 * on "dolibarr/admin/modules.php" file, but when it's installed internally (in each module) then the class
 * will be included from the first loaded module & in the rest of modules the inclusion will be stopped by
 * the if condition above, otherwise you will get this error: DolibaseModule class already exists.
 *
 * So the issue here is, if a module with an older version of Dolibase is loaded first & one of your modules
 * requires a new version of this class to work properly, then you may face some errors like:
 * Undefined function or attribute xxx in DolibaseModule class.
 *
 * Solutions:
 *
 * - Use Dolibase module builder (will generate a custom class copy in your module directory).
 *
 * - Keep always your modules up-to-date with the latest version of Dolibase (painful solution).
 *
 * - Each time you add a function in this class/file, you need to copy & paste it into your module(s) class also.
 *
 * - Use namespaces to separate module file(s) from each others (complicated solution, may even not work on Dolibarr).
 *
 * P.S: This issue affects only DolibaseModule & Widget class & not the other classes of Dolibase.
 */

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
		global $dolibase_config, $langs, $conf;

		// Check if config array is empty
		if (empty($dolibase_config)) dolibase_error('Module configuration not found.', true);

		// Save module config for further use
		$this->config = $dolibase_config;

		// Load lang files
		$langs->load("module@".$this->config['langs']['path']);

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
		$this->version         = ($this->config['module']['version'] == 'dolibase' ? $this->config['main']['version'] : $this->config['module']['version']);
		$this->const_name      = "MAIN_MODULE_".strtoupper($this->name);
		$this->special         = 0;
		$this->picto           = $this->config['module']['picture']."@".$this->config['module']['folder'];

		// Module parts (css, js, ...)
		$this->module_parts    = array(
			'css'   => array(),
			'js'    => array(),
			'hooks' => array()
		);

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
		$this->addons = array(
			'num' => array(),
			'doc' => array()
		);

		// Cron Jobs
		$this->cronjobs = array();

		// Enable triggers
		if (isset($this->config['module']['enable_triggers']) && $this->config['module']['enable_triggers']) {
			$this->module_parts['triggers'] = 1;
		}

		// Load module settings
		$this->loadSettings();

		// Check for updates
		if (! $conf->global->DOLIBASE_DISABLE_CHECK_FOR_UPDATES) {
			$this->checkUpdates($langs);
		}
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
					$this->version .= ' <a href="'.$this->config['module']['url'].'" title="'.$langs->trans('NewVersionAvailable', $module_version[1]).'" target="_blank"><img src="'.dolibase_buildurl('core/img/update.png').'" class="valignmiddle" width="24" alt="'.$module_version[1].'"></a>';
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

		// Enable module for external users
		if (isset($this->config['module']['enable_for_external_users']) && $this->config['module']['enable_for_external_users']) {
			$this->enableModuleForExternalUsers($this->config['module']['rights_class']);
		}

		return $this->_init($sql, $options);
	}

	/**
	 * Enable module for external users
	 *
	 * @param     $module_rights_class     Module rights class
	 */
	protected function enableModuleForExternalUsers($module_rights_class)
	{
		global $conf;

		if (empty($conf->global->MAIN_MODULES_FOR_EXTERNAL) || strpos($conf->global->MAIN_MODULES_FOR_EXTERNAL, $module_rights_class) === false) {
			$value = empty($conf->global->MAIN_MODULES_FOR_EXTERNAL) ? $module_rights_class : join(',', array($conf->global->MAIN_MODULES_FOR_EXTERNAL, $module_rights_class));
			dolibarr_set_const($this->db, 'MAIN_MODULES_FOR_EXTERNAL', $value, 'chaine', 1, '', $conf->entity);
		}
	}

	/**
	 * Disable module for external users
	 *
	 * @param     $module_rights_class     Module rights class
	 */
	protected function disableModuleForExternalUsers($module_rights_class)
	{
		global $conf;

		if (! empty($conf->global->MAIN_MODULES_FOR_EXTERNAL)) {
			$modules_list = explode(',', $conf->global->MAIN_MODULES_FOR_EXTERNAL);
			$found = false;
			foreach ($modules_list as $key => $value)
			{
				if ($value == $module_rights_class) {
					unset($modules_list[$key]);
					$found = true;
				}
			}
			if ($found) {
				$value = empty($modules_list) ? '' : join(',', $modules_list);
				dolibarr_set_const($this->db, 'MAIN_MODULES_FOR_EXTERNAL', $value, 'chaine', 1, '', $conf->entity);
			}
		}
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
					delDocumentModel($addon['name'], $type);
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

		// Disable module for external users
		if (isset($this->config['module']['enable_for_external_users']) && ! $this->config['module']['enable_for_external_users']) {
			$this->disableModuleForExternalUsers($this->config['module']['rights_class']);
		}

		return $this->_remove($sql, $options);
	}

	/**
	 * Add a constant
	 *
	 * @param     $name     constant name
	 * @param     $value    constant value
	 * @param     $desc     constant description / note
	 * @param     $type     constant type
	 * @return    $this
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

		return $this;
	}

	/**
	 * Add a widget
	 *
	 * @param     $widget_filename           widget filename
	 * @param     $note                      widget note
	 * @param     $enabled_by_default_on     where to enable the widget by default
	 * @return    $this
	 */
	public function addWidget($widget_filename, $note = '', $enabled_by_default_on = 'Home')
	{
		$this->boxes[] = array(
			'file' => $widget_filename.'@'.$this->config['module']['folder'],
			'note' => $note,
			'enabledbydefaulton' => $enabled_by_default_on
		);

		return $this;
	}

	/**
	 * Add a permission
	 *
	 * @param     $name                   permission name
	 * @param     $desc                   permission description
	 * @param     $type                   permission type: 'r', 'c', 'm', 'd'
	 * @param     $enabled_by_default     enable the permission by default for all users
	 * @return    $this
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

		return $this;
	}

	/**
	 * Add a sub permission
	 *
	 * @param     $perm_name              permission name
	 * @param     $subperm_name           sub permission name
	 * @param     $desc                   permission description
	 * @param     $type                   permission type: 'r', 'c', 'm', 'd'
	 * @param     $enabled_by_default     enable the permission by default for all users
	 * @return    $this
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

		return $this;
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
	 * @return    $this
	 */
	public function addTopMenu($name, $title, $url, $perms = '1', $enabled = '1', $position = 100, $target = '')
	{
		$this->addMenu('top', 0, $name, '', $title, $url, $position, $enabled, $perms, $target);

		return $this;
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
	 * @return    $this
	 */
	public function addLeftMenu($main_menu, $name, $title, $url, $perms = '1', $enabled = '1', $position = 100, $target = '')
	{
		$this->addMenu('left', 'fk_mainmenu='.$main_menu, $main_menu, $name, $title, $url, $position, $enabled, $perms, $target);

		return $this;
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
	 * @return    $this
	 */
	public function addLeftSubMenu($main_menu, $left_menu, $name, $title, $url, $perms = '1', $enabled = '1', $position = 100, $target = '')
	{
		$this->addMenu('left', 'fk_mainmenu='.$main_menu.',fk_leftmenu='.$left_menu, $main_menu, $name, $title, $url, $position, $enabled, $perms, $target);

		return $this;
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
	 * @return    $this
	 */
	public function addCssFile($css_filename)
	{
		$this->addModulePart('css', $this->config['module']['folder'].'/css/'.$css_filename);

		return $this;
	}

	/**
	 * Add an array of CSS files
	 *
	 * @param     $css_files_array     css files array
	 * @return    $this
	 */
	public function addCssFiles($css_files_array)
	{
		foreach ($css_files_array as $css_file) {
			$this->addCssFile($css_file);
		}

		return $this;
	}

	/**
	 * Add a JS file
	 *
	 * @param     $js_filename     javascript filename
	 * @return    $this
	 */
	public function addJsFile($js_filename)
	{
		$this->addModulePart('js', $this->config['module']['folder'].'/js/'.$js_filename);

		return $this;
	}

	/**
	 * Add an array of JS files
	 *
	 * @param     $js_files_array     javascript files array
	 * @return    $this
	 */
	public function addJsFiles($js_files_array)
	{
		foreach ($js_files_array as $js_file) {
			$this->addJsFile($js_file);
		}

		return $this;
	}

	/**
	 * Enable a hook
	 *
	 * @param     $hook      dolibarr hook name: 'toprightmenu', 'main', ...
	 * @return    $this
	 */
	public function enableHook($hook)
	{
		$this->addModulePart('hooks', $hook);

		return $this;
	}

	/**
	 * Enable an array of hooks
	 *
	 * @param     $hooks_array      hooks array
	 * @return    $this
	 */
	public function enableHooks($hooks_array)
	{
		foreach ($hooks_array as $hook) {
			$this->enableHook($hook);
		}

		return $this;
	}

	/**
	 * Enable triggers
	 *
	 * @return    $this
	 */
	public function enableTriggers()
	{
		$this->module_parts['triggers'] = 1;

		return $this;
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
	 * @return    $this
	 */
	public function addDictionary($table_name, $table_label, $select_fields = 'rowid, label, active', $table_sort = 'label ASC', $fields_to_show = 'label', $fields_to_update = 'label', $fields_to_insert = 'label', $table_pk_field = 'rowid', $fields_help = array())
	{
		global $conf;

		$dict_table = MAIN_DB_PREFIX.$table_name;
		$modulepart = get_modulepart();

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

		return $this;
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
	 * @return    $this
	 */
	public function activateNumModel($name, $const_prefix = '')
	{
		$this->addons['num'][] = array('name' => $name, 'const_prefix' => $const_prefix);

		return $this;
	}

	/**
	 * Activate a document model
	 *
	 * @param     $name             document model name
	 * @param     $type             document model type
	 * @param     $const_prefix     document model constant prefix
	 * @return    $this
	 */
	public function activateDocModel($name, $type = '', $const_prefix = '')
	{
		$this->addons['doc'][] = array('name' => $name, 'type' => $type, 'const_prefix' => $const_prefix);

		return $this;
	}

	/**
	 * Add a cron job
	 *
	 * @param     $label                 Job label
	 * @param     $type                  Job type, possible values: 'command', 'method'
	 * @param     $command               Job shell command (if $type = 'command')
	 * @param     $class                 Job class (if $type = 'method'), e.: '/mymodule/class/myobject.class.php'
	 * @param     $object_name           Object name (if $type = 'method'), e.: 'MyObject'
	 * @param     $object_method         Object method (if $type = 'method'), e.: 'doScheduledJob'
	 * @param     $method_parameters     Method parameters (if $type = 'method'), e.: 'param1, param2'
	 * @param     $comment               Job comment
	 * @param     $frequency             Job frequency or execution time, e.: 2 (if $frequency_unit = 3600 it will be considered as every 2 hours)
	 * @param     $frequency_unit        Job frequency unit, e.: 3600 (1 hour), 3600*24 (1 day), 3600*24*7 (1 week)
	 * @param     $status                Job status at module installation: 0 = disabled, 1 = enabled
	 * @param     $priority              Job priority (number from 0 to 100)
	 */
	protected function addCronJob($label, $type, $command = '', $class = '', $object_name = '', $object_method = '', $method_parameters = '', $comment = '', $frequency = 2, $frequency_unit = 3600, $status = 0, $priority = 0)
	{
		$this->cronjobs[] = array(
			'label' => $label,
			'jobtype' => $type,
			'class' => $class,
			'objectname' => $object_name,
			'method' => $object_method,
			'command' => $command,
			'parameters' => $method_parameters,
			'comment' => $comment,
			'frequency' => $frequency,
			'unitfrequency' => $frequency_unit,
			'status' => $status,
			'priority' => $priority,
			'test' => true
		);
	}

	/**
	 * Add a cron job using a command
	 *
	 * @param     $label                 Job label
	 * @param     $command               Job shell command
	 * @param     $frequency             Job frequency or execution time, e.: 2 (if $frequency_unit = 3600 it will be considered as every 2 hours)
	 * @param     $frequency_unit        Job frequency unit, e.: 3600 (1 hour), 3600*24 (1 day), 3600*24*7 (1 week)
	 * @param     $comment               Job comment
	 * @param     $priority              Job priority (number from 0 to 100)
	 * @param     $status                Job status at module installation: 0 = disabled, 1 = enabled
	 * @return    $this
	 */
	public function addCronCommand($label, $command, $frequency, $frequency_unit, $comment = '', $priority = 0, $status = 1)
	{
		$this->addCronJob($label, 'command', $command, '', '', '', '', $comment, $frequency, $frequency_unit, $status, $priority);

		return $this;
	}

	/**
	 * Add a cron job using a method
	 *
	 * @param     $label                 Job label
	 * @param     $class                 Job class, e.: '/mymodule/class/myobject.class.php'
	 * @param     $object_name           Object name, e.: 'MyObject'
	 * @param     $object_method         Object method, e.: 'doScheduledJob'
	 * @param     $method_parameters     Method parameters, e.: 'param1, param2'
	 * @param     $frequency             Job frequency or execution time, e.: 2 (if $frequency_unit = 3600 it will be considered as every 2 hours)
	 * @param     $frequency_unit        Job frequency unit, e.: 3600 (1 hour), 3600*24 (1 day), 3600*24*7 (1 week)
	 * @param     $comment               Job comment
	 * @param     $priority              Job priority (number from 0 to 100)
	 * @param     $status                Job status at module installation: 0 = disabled, 1 = enabled
	 * @return    $this
	 */
	public function addCronMethod($label, $class, $object_name, $object_method, $method_parameters, $frequency, $frequency_unit, $comment = '', $priority = 0, $status = 1)
	{
		$this->addCronJob($label, 'method', '', $class, $object_name, $object_method, $method_parameters, $comment, $frequency, $frequency_unit, $status, $priority);

		return $this;
	}
}

}
