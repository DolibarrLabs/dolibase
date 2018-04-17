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

dolibase_include_once('/core/class/page.php');

/**
 * AboutPage class
 */

class AboutPage extends Page
{
	/**
	 * @var string Page title
	 */
	protected $title = "About";
	/**
	 * @var string Access permission
	 */
	protected $access_permission = '$user->admin';

	/**
	 * Constructor
	 * 
	 */
	public function __construct()
	{
		parent::__construct($this->title, $this->access_permission);
	}

	/**
	 * Generate page body
	 *
	 */
	protected function generate()
	{
		global $langs, $dolibase_config;

		// Load lang files
		$langs->load("admin");
		$langs->load("about_page@".$dolibase_config['module_folder']);

		// Add sub title
		$linkback = '<a href="'.DOL_URL_ROOT.'/admin/modules.php?mainmenu=home">'.$langs->trans("BackToModuleList").'</a>';
		$this->addSubTitle($this->title, 'title_generic.png', $linkback);

		// Add default tabs
		$this->addTab("Settings", "/".$dolibase_config['module_folder']."/admin/".$dolibase_config['setup_page_url']);
		$this->addTab("About", "/".$dolibase_config['module_folder']."/admin/".$dolibase_config['about_page_url'], true);

		// Generate tabs
		$this->generateTabs();
	}

	/**
	 * Print module informations
	 *
	 * @param     $picture     Picture to show, prefered size: 128x128 (leave empty to use the module picture)
	 */
	public function printModuleInformations($picture = '')
	{
		global $langs, $dolibase_config;

		if (empty($picture)) {
			$picture = 'object_'.$dolibase_config['module_picture'];
		}

		print '<div style="float: left; margin-right: 20px;"><img src="../img/'.$picture.'" /></div>';
		print '<br/>';
		print '<div>';
		print '<a href="'.$dolibase_config['module_url'].'" target="_blank">';
		print '<b>'.$langs->trans($dolibase_config['module_name']).'</b>';
		print '</a> : '.$langs->trans($dolibase_config['module_desc']);
		print '<br/><br/>'.$langs->trans('DevelopedBy').' <a href="'.$dolibase_config['editor_url'].'" target="_blank">'.$dolibase_config['editor_name'].'</a>';
		print '. '.$langs->trans('DolibaseVersion').' <a href="'.DOLIBASE_LINK.'" target="_blank">'.DOLIBASE_VERSION.'</a>';
		print '<br/><br/>'.$langs->trans('ForAnyQuestions').' <a href="mailto:'.$dolibase_config['editor_email'].'">'.$dolibase_config['editor_email'].'</a>';
		print '<br><br>'.$langs->trans('FindMyModules').' <a href="'.$dolibase_config['dolistore_url'].'" target="_blank">'.$langs->trans('Dolistore').'</a>';
		print '</div>';
		print '<br/><br/>';
	}
}