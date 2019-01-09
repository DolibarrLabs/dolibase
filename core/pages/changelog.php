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
 * ChangelogPage class
 */

class ChangelogPage extends Page
{
	/**
	 * @var boolean used to add extrafields tab
	 */
	protected $add_extrafields_tab = false;


	/**
	 * Constructor
	 * 
	 * @param     $page_title                 HTML page title
	 * @param     $access_perm                Access permission
	 * @param     $add_extrafields_tab        Add extrafields tab
	 */
	public function __construct($page_title = 'Changelog', $access_perm = '$user->admin', $add_extrafields_tab = false)
	{
		global $langs, $dolibase_config;

		// Load lang files
		$langs->load('admin');
		$langs->load('changelog_page@'.$dolibase_config['main']['path']);

		// Set attributes
		$this->add_extrafields_tab = $add_extrafields_tab;

		// Add some custom css
		$this->appendToHead('<link rel="stylesheet" type="text/css" href="'.dolibase_buildurl('core/css/changelog.css.php').'">'."\n");

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
		$linkback = '<a href="'.DOL_URL_ROOT.'/admin/modules.php?mainmenu=home">'.$langs->trans('BackToModuleList').'</a>';
		$this->addSubTitle($this->title, 'title_generic.png', $linkback);

		// Add default tabs
		if (empty($this->tabs)) {
			$this->addTab('Settings', $dolibase_config['module']['folder'].'/admin/'.$dolibase_config['other']['setup_page'].'?mainmenu=home');
			if ($this->add_extrafields_tab) {
				$this->addTab('ExtraFields', $dolibase_config['module']['folder'].'/admin/extrafields.php?mainmenu=home');
			}
			$this->addTab('Changelog', $dolibase_config['module']['folder'].'/admin/changelog.php?mainmenu=home', true);
			$this->addTab('About', $dolibase_config['module']['folder'].'/admin/'.$dolibase_config['other']['about_page'].'?mainmenu=home');
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
	 * Print module changelog
	 *
	 * @return    $this
	 */
	public function printChangelog()
	{
		$template_path = dolibase_buildpath('core/tpl/module_changelog.php');

		$this->showTemplate($template_path, true, true);

		return $this;
	}
}
