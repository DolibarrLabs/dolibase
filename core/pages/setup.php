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
 * SetupPage class
 */

class SetupPage extends Page
{
	/**
	 * @var string Page title
	 */
	protected $title = "Setup";
	/**
	 * @var boolean Page is admin only
	 */
	protected $admin_only = true;
	/**
	 * @var boolean used to colorise options table rows (odd | peer)
	 */
	protected $odd = true;
	/**
	 * @var boolean used to close opened options table
	 */
	protected $close_table = false;

	/**
	 * Constructor
	 * 
	 */
	public function __construct()
	{
		//parent::__construct($this->title, $this->admin_only);

		// Add some custom css
		$this->head = "<style>
						.disabled {
	                        cursor: not-allowed !important;
	                        opacity: 0.4;
	                        filter: alpha(opacity=40);
	                        box-shadow: none;
	                        -webkit-box-shadow: none;
	                        -moz-box-shadow: none;
	                    }
	                    .nopointerevents {
	                        pointer-events: none;
	                    }
	                </style>";
	}

	/**
	 * Load default actions
	 *
	 */
	protected function loadDefaultActions()
	{
		global $conf, $db;

		// Libraries
		require_once DOL_DOCUMENT_ROOT . "/core/lib/admin.lib.php";

		// Parameters
		$action = GETPOST('action', 'alpha');
		$value = GETPOST('value', 'alpha');

		// Actions
		if (preg_match('/set_(.*)/', $action, $reg))
		{
			$code = $reg[1];
			if (dolibarr_set_const($db, $code, GETPOST($code), 'chaine', 0, '', $conf->entity) > 0)
			{
				header("Location: ".$_SERVER["PHP_SELF"]);
				exit;
			}
			else
			{
				dol_print_error($db);
			}
		}
		else if (preg_match('/del_(.*)/', $action, $reg))
		{
			$code = $reg[1];
			if (dolibarr_del_const($db, $code, $conf->entity) > 0)
			{
				Header("Location: ".$_SERVER["PHP_SELF"]);
				exit;
			}
			else
			{
				dol_print_error($db);
			}
		}
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
		$langs->load("setup_page@".$dolibase_config['module_folder']);

		// Add default tabs
		$this->addTab("Settings", "/".$dolibase_config['module_folder']."/admin/".$dolibase_config['setup_page_url'], true);
		$this->addTab("About", "/".$dolibase_config['module_folder']."/admin/".$dolibase_config['about_page_url']);

		// Add sub title
		$linkback = '<a href="'.DOL_URL_ROOT.'/admin/modules.php?mainmenu=home">'.$langs->trans("BackToModuleList").'</a>';
		$this->addSubTitle($this->title, 'title_setup.png', $linkback);

		// Generate tabs
		$this->generateTabs();
	}

	/**
	 * Create a new table for options
	 *
	 */
	public function newOptionsTable()
	{
		global $langs;

		// Close last opened table if true
		if ($this->close_table) $this->closeTable();

		print '<table class="noborder allwidth">'."\n";
		print '<tr class="liste_titre">'."\n";
		print '<td>'.$langs->trans("Option").'</td>'."\n";
		print '<td align="center" width="20">&nbsp;</td>';
		print '<td align="center" width="100">'.$langs->trans("Value").'</td>'."\n";
		print '</tr>'."\n";

		$this->close_table = true; // a table have been opened & should be closed
	}

	/**
	 * Add a new switch option
	 *
	 * @param     $option_desc       Option description
	 * @param     $const_name        Option constant name
	 * @param     $disabled          disable option or not
	 * @param     $morehtmlright     more HTML to add on the right of the option
	 */
	public function addSwitchOption($option_desc, $const_name, $disabled = false, $morehtmlright = '')
	{
		global $conf, $langs, $bc;

		$this->odd = !$this->odd;
		$more_attr = $disabled ? ' class="disabled nopointerevents"' : '';

		print '<tr '.$bc[$this->odd].'><td'.$more_attr.'>'.$langs->trans($option_desc).$morehtmlright.'</td>'."\n";
		print '<td'.$more_attr.' align="center">&nbsp;</td>'."\n";
		print '<td'.$more_attr.' align="right">'."\n";
		if (empty($conf->global->$const_name))
		{
		    print '<a href="'.$_SERVER['PHP_SELF'].'?action=set_'.$const_name.'&amp;'.$const_name.'=1">'.img_picto($langs->trans("Disabled"),'switch_off').'</a>'."\n";
		}
		else
		{
		    print '<a href="'.$_SERVER['PHP_SELF'].'?action=set_'.$const_name.'&amp;'.$const_name.'=0">'.img_picto($langs->trans("Enabled"),'switch_on').'</a>'."\n";
		}
		print "&nbsp;&nbsp;&nbsp;&nbsp;</td>\n</tr>\n";
	}

	/**
	 * Add a new text option
	 *
	 * @param     $option_desc       Option description
	 * @param     $const_name        Option constant name
	 * @param     $size              Option textbox size
	 * @param     $width             Option last column/td width
	 */
	public function addTextOption($option_desc, $const_name, $size = 16, $width = 250)
	{
		global $conf, $langs, $bc;

		$this->odd = !$this->odd;

		print '<tr '.$bc[$this->odd].'><td>'.$langs->trans($option_desc).$morehtmlright.'</td>'."\n";
		print '<td align="center">&nbsp;</td>'."\n";
		print '<td width="'.$width.'" align="right">'."\n";
		print '<form action="'.$_SERVER["PHP_SELF"].'" method="POST">'."\n";
		print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'" />'."\n";
		print '<input type="hidden" name="action" value="set_'.$const_name.'" />'."\n";
		print '<input type="text" size="'.$size.'" class="flat" name="'.$const_name.'" value="'.$conf->global->$const_name.'">'."\n";
		print '&nbsp;&nbsp;<input type="submit" class="button" value="'.$langs->trans("Modify").'">&nbsp;&nbsp;'."\n";
		print "</form>\n</td>\n</tr>\n";
	}

	/**
	 * Add a new number only option
	 *
	 * @param     $option_desc       Option description
	 * @param     $const_name        Option constant name
	 * @param     $min               Option minimum number
	 * @param     $max               Option maximum number
	 * @param     $width             Option last column/td width
	 */
	public function addNumberOption($option_desc, $const_name, $min = 0, $max = 100, $width = 250)
	{
		global $conf, $langs, $bc;

		$this->odd = !$this->odd;

		print '<tr '.$bc[$this->odd].'><td>'.$langs->trans($option_desc).$morehtmlright.'</td>'."\n";
		print '<td align="center">&nbsp;</td>'."\n";
		print '<td width="'.$width.'" align="right">'."\n";
		print '<form action="'.$_SERVER["PHP_SELF"].'" method="POST">'."\n";
		print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'" />'."\n";
		print '<input type="hidden" name="action" value="set_'.$const_name.'" />'."\n";
		print '<input type="number" min="'.$min.'" max="'.$max.'" class="flat" name="'.$const_name.'" value="'.$conf->global->$const_name.'">'."\n";
		print '&nbsp;&nbsp;<input type="submit" class="button" value="'.$langs->trans("Modify").'">&nbsp;&nbsp;'."\n";
		print "</form>\n</td>\n</tr>\n";
	}

	/**
	 * Add a new list option
	 *
	 * @param     $option_desc       Option description
	 * @param     $const_name        Option constant name
	 * @param     $list              Options list array
	 * @param     $width             Option last column/td width
	 */
	public function addListOption($option_desc, $const_name, $list, $width = 250)
	{
		global $conf, $langs, $bc, $db;

		$this->odd = !$this->odd;

		print '<tr '.$bc[$this->odd].'><td>'.$langs->trans($option_desc).$morehtmlright.'</td>'."\n";
		print '<td align="center">&nbsp;</td>'."\n";
		print '<td width="'.$width.'" align="right">'."\n";
		print '<form action="'.$_SERVER["PHP_SELF"].'" method="POST">'."\n";
		print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'" />'."\n";
		print '<input type="hidden" name="action" value="set_'.$const_name.'" />'."\n";
		// Translate list options
		foreach ($list as $key => $value) {
			$list[$key] = $langs->trans($list[$key]);
		}
		$form = new Form($db);
		print $form->selectarray($const_name, $list, $conf->global->$const_name);
		print '&nbsp;&nbsp;&nbsp;<input type="submit" class="button" value="'.$langs->trans("Modify").'">&nbsp;&nbsp;'."\n";
		print "</form>\n</td>\n</tr>\n";
	}

	/**
	 * Add a new color picker option
	 *
	 * @param     $option_desc       Option description
	 * @param     $const_name        Option constant name
	 * @param     $width             Option last column/td width
	 */
	public function addColorOption($option_desc, $const_name, $width = 250)
	{
		global $conf, $langs, $bc, $db;

		include_once DOL_DOCUMENT_ROOT . '/core/class/html.formother.class.php';

		$this->odd = !$this->odd;

		print '<tr '.$bc[$this->odd].'><td>'.$langs->trans($option_desc).$morehtmlright.'</td>'."\n";
		print '<td align="center">&nbsp;</td>'."\n";
		print '<td width="'.$width.'" align="right">'."\n";
		print '<form action="'.$_SERVER["PHP_SELF"].'" method="POST">'."\n";
		print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'" />'."\n";
		print '<input type="hidden" name="action" value="set_'.$const_name.'" />'."\n";
		$formother = new FormOther($db);
		print $formother->selectColor(colorArrayToHex(colorStringToArray($conf->global->$const_name, array()), ''), $const_name, 'formcolor', 1);
		print '&nbsp;&nbsp;&nbsp;<input type="submit" class="button" value="'.$langs->trans("Modify").'">&nbsp;&nbsp;'."\n";
		print "</form>\n</td>\n</tr>\n";
	}

	/**
	 * Close html table
	 *
	 */
	protected function closeTable()
	{
		print "</table><br>\n";

		$this->close_table = false;
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
		if ($this->close_table) $this->closeTable();
		parent::addSubTitle($title, $picture, $morehtmlright);
	}

	/**
	 * Generate page end
	 *
	 */
	public function end()
	{
		if ($this->close_table) $this->closeTable();
		parent::end();
	}
}