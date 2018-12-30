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

dolibase_include_once('core/class/page.php');

/**
 * AboutPage class
 */

class AboutPage extends Page
{
	/**
	 * @var boolean used to add extrafields tab
	 */
	protected $add_extrafields_tab = false;
	/**
	 * @var boolean used to add changelog tab
	 */
	protected $add_changelog_tab = false;


	/**
	 * Constructor
	 * 
	 * @param     $page_title                 HTML page title
	 * @param     $access_perm                Access permission
	 * @param     $add_extrafields_tab        Add extrafields tab
	 * @param     $add_changelog_tab          Add changelog tab
	 */
	public function __construct($page_title = 'About', $access_perm = '$user->admin', $add_extrafields_tab = false, $add_changelog_tab = false)
	{
		global $langs, $dolibase_config;

		// Load lang files
		$langs->load("admin");
		$langs->load("about_page@".$dolibase_config['langs']['path']);

		// Set attributes
		$this->add_extrafields_tab = $add_extrafields_tab;
		$this->add_changelog_tab   = $add_changelog_tab;

		// Add some custom css
		$this->appendToHead('<link rel="stylesheet" type="text/css" href="'.dolibase_buildurl('core/css/about.css.php').'">'."\n");

		parent::__construct($page_title, $access_perm);
	}

	/**
	 * Generate page body
	 *
	 */
	protected function generate()
	{
		global $langs, $dolibase_config;

		// Add sub title
		$linkback = '<a href="'.DOL_URL_ROOT.'/admin/modules.php?mainmenu=home">'.$langs->trans("BackToModuleList").'</a>';
		$this->addSubTitle($this->title, 'title_generic.png', $linkback);

		// Add default tabs
		if (empty($this->tabs)) {
			$this->addTab("Settings", $dolibase_config['module']['folder']."/admin/".$dolibase_config['other']['setup_page']."?mainmenu=home");
			if ($this->add_extrafields_tab) {
				$this->addTab("ExtraFields", $dolibase_config['module']['folder']."/admin/extrafields.php?mainmenu=home");
			}
			if ($this->add_changelog_tab) {
				$this->addTab("Changelog", $dolibase_config['module']['folder']."/admin/changelog.php?mainmenu=home");
			}
			$this->addTab("About", $dolibase_config['module']['folder']."/admin/".$dolibase_config['other']['about_page']."?mainmenu=home", true);
		}

		parent::generate();
	}

	/**
	 * Generate tabs
	 *
	 * @param     $noheader     -1 or 0=Add tab header, 1=no tab header.
	 */
	protected function generateTabs($noheader = -1)
	{
		parent::generateTabs($noheader);
	}

	/**
	 * Print module informations
	 *
	 * @param     $picture     Picture to show, prefered size: 128x128 (leave empty to use the module picture)
	 */
	public function printModuleInformations($picture = '')
	{
		$template_path = dolibase_buildpath('core/tpl/about_module.php');
		$template_params = array(
			'picture' => $picture
		);

		$this->showTemplate($template_path, true, true, $template_params);
	}
}
