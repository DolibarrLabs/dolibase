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
 * Module class
 */

class Module
{
	/**
	 * @var DolibarrModules Dolibarr module object
	 */
	private $module;
	/**
	 * @var array Dolibase config array
	 */
	public $config;

	/**
	 * Constructor
	 * 
	 * @param     $mod        Dolibarr module object
	 * @param     $db         Database handler
	 */
	public function __construct(&$mod)
	{
		global $dolibase_config, $db;

		// Check if config array is empty
		if (empty($dolibase_config)) die('Dolibase::Module::Error module configuration not found.');

		// Save module object & config for further use
		$this->module = $mod;
		$this->config = $dolibase_config;

		// Module configuration
		$this->module->db              = $db;
		$this->module->editor_name     = $this->config['editor_name'];
		$this->module->editor_url      = $this->config['editor_url'];
		$this->module->numero          = $this->config['module_number'];
		$this->module->rights_class    = $this->config['rights_class'];
		$this->module->family          = $this->config['module_family'];
		$this->module->module_position = $this->config['module_position'];
		$this->module->name            = $this->config['module_name'];
		$this->module->description     = $this->config['module_desc'];
		$this->module->version         = $this->config['module_version'];
		$this->module->const_name      = "MAIN_MODULE_".strtoupper($this->module->name);
		$this->module->special         = 0;
		$this->module->picto           = $this->config['module_picture']."@".$this->config['module_folder'];

		// Module parts (css, js, ...)
		$this->module->module_parts    = array();

		// Data directories to create when module is enabled
		$this->module->dirs            = $this->config['module_dirs'];

		// Config page
		$this->module->config_page_url = array($this->config['setup_page_url']."@".$this->config['module_folder']);

		// Dependencies
		$this->module->need_dolibarr_version = $this->config['need_dolibarr'];
		$this->module->phpmin                = $this->config['need_php'];
		$this->module->depends               = $this->config['module_depends'];
		$this->module->requiredby            = $this->config['required_by'];
		$this->module->conflictwith          = $this->config['conflict_with'];
		$this->module->langfiles             = $this->config['lang_files'];

		// Constants
		$this->module->const = array();

		// Boxes / widgets
		$this->module->boxes = array();

		// Permissions
		$this->module->rights = array();

		// Menu
		$this->module->menu = array();
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
		$this->module->const[] = array(
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
	 * @param     $enabled_by_default_on     where to enable the widget by default
	 */
	public function addWidget($widget_filename, $enabled_by_default_on = 'Home')
	{
		$this->module->boxes[] = array(
	                                'file' => $widget_filename.'@'.$this->config['module_folder'],
	                                'note' => '',
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
		$this->module->rights[] = array(
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
		$this->module->rights[] = array(
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
		return (int)$this->config['module_number'] + count($this->module->rights) + 1;
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
		$this->module->menu[] = array(
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
	 * Add a module part
	 *
	 * @param     $module_part     module part: 'hooks', 'css', 'js'
	 * @param     $value           module part value (could be a css or js filenames, a hook, etc)
	 */
	private function addModulePart($module_part, $value)
	{
		$this->module->module_parts[$module_part][] = $value;
	}
}