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
include_once DOL_DOCUMENT_ROOT . '/core/class/html.formother.class.php';

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
	 * @var string Access permission
	 */
	protected $access_permission = '$user->admin';
	/**
	 * @var boolean used to colorise options rows (odd | peer)
	 */
	protected $odd = true;
	/**
	 * @var object used to call Dolibarr form functions
	 */
	public $form;
	/**
	 * @var object used to call Dolibarr color picker functions
	 */
	public $formother;

	/**
	 * Constructor
	 * 
	 */
	public function __construct()
	{
		global $db;

		// Initialise form objects
		$this->form = new Form($db);
		$this->formother = new FormOther($db);

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

		parent::__construct($this->title, $this->access_permission);
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

		// Add sub title
		$linkback = '<a href="'.DOL_URL_ROOT.'/admin/modules.php?mainmenu=home">'.$langs->trans("BackToModuleList").'</a>';
		$this->addSubTitle($this->title, 'title_setup.png', $linkback);

		// Add default tabs
		$this->addTab("Settings", "/".$dolibase_config['module_folder']."/admin/".$dolibase_config['setup_page_url'], true);
		$this->addTab("About", "/".$dolibase_config['module_folder']."/admin/".$dolibase_config['about_page_url']);

		// Generate tabs
		$this->generateTabs();
	}

	/**
	 * Create a new table for options
	 *
	 */
	public function newOptionsTable()
	{
		$options_table_cols = array(
								array('name' => 'Option', 'attr' => ''),
								array('name' => '&nbsp;', 'attr' => 'align="center" width="20"'),
								array('name' => 'Value', 'attr' => 'align="center" width="100"')
							);

		$this->openTable($options_table_cols);
	}

	/**
	 * Add a new option
	 *
	 * @param     $option_desc       Option description
	 * @param     $option_content    Option content, it can be HTML or even a string
	 * @param     $const_name        Option constant name
	 * @param     $morehtmlright     more HTML to add on the right of the option description
	 * @param     $width             Option last column/td width
	 */
	public function addOption($option_desc, $option_content, $const_name = '', $morehtmlright = '', $width = 250)
	{
		global $conf, $langs, $bc;

		$this->odd = !$this->odd;

		print '<tr '.$bc[$this->odd].'><td>'.$langs->trans($option_desc).$morehtmlright.'</td>'."\n";
		print '<td align="center">&nbsp;</td>'."\n";
		print '<td width="'.$width.'" align="right">'."\n";
		if (! empty($const_name)) {
			print '<form action="'.$_SERVER["PHP_SELF"].'" method="POST">'."\n";
			print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'" />'."\n";
			print '<input type="hidden" name="action" value="set_'.$const_name.'" />'."\n";
		}
		print $option_content."\n";
		if (! empty($const_name)) {
			print '&nbsp;&nbsp;<input type="submit" class="button" value="'.$langs->trans("Modify").'">&nbsp;&nbsp;'."\n";
		}
		print "</form>\n</td>\n</tr>\n";
	}

	/**
	 * Add a new switch option
	 *
	 * @param     $option_desc       Option description
	 * @param     $const_name        Option constant name
	 * @param     $disabled          disable option or not
	 * @param     $morehtmlright     more HTML to add on the right of the option description
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
	 * @param     $morehtmlright     more HTML to add on the right of the option description
	 * @param     $size              Option textbox size
	 * @param     $width             Option last column/td width
	 */
	public function addTextOption($option_desc, $const_name, $morehtmlright = '', $size = 16, $width = 250)
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
	 * @param     $morehtmlright     more HTML to add on the right of the option description
	 * @param     $width             Option last column/td width
	 */
	public function addNumberOption($option_desc, $const_name, $min = 0, $max = 100, $morehtmlright = '', $width = 250)
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
	 * @param     $morehtmlright     more HTML to add on the right of the option description
	 * @param     $width             Option last column/td width
	 */
	public function addListOption($option_desc, $const_name, $list, $morehtmlright = '', $width = 250)
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
			$list[$key] = $langs->trans($value);
		}
		print $this->form->selectarray($const_name, $list, $conf->global->$const_name);
		print '&nbsp;&nbsp;&nbsp;<input type="submit" class="button" value="'.$langs->trans("Modify").'">&nbsp;&nbsp;'."\n";
		print "</form>\n</td>\n</tr>\n";
	}

	/**
	 * Add a new color picker option
	 *
	 * @param     $option_desc       Option description
	 * @param     $const_name        Option constant name
	 * @param     $morehtmlright     more HTML to add on the right of the option description
	 * @param     $width             Option last column/td width
	 */
	public function addColorOption($option_desc, $const_name, $morehtmlright = '', $width = 250)
	{
		global $conf, $langs, $bc, $db;

		$this->odd = !$this->odd;

		print '<tr '.$bc[$this->odd].'><td>'.$langs->trans($option_desc).$morehtmlright.'</td>'."\n";
		print '<td align="center">&nbsp;</td>'."\n";
		print '<td width="'.$width.'" align="right">'."\n";
		print '<form action="'.$_SERVER["PHP_SELF"].'" method="POST">'."\n";
		print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'" />'."\n";
		print '<input type="hidden" name="action" value="set_'.$const_name.'" />'."\n";
		print $this->formother->selectColor(colorArrayToHex(colorStringToArray($conf->global->$const_name, array()), ''), $const_name, 'formcolor', 1);
		print '&nbsp;&nbsp;&nbsp;<input type="submit" class="button" value="'.$langs->trans("Modify").'">&nbsp;&nbsp;'."\n";
		print "</form>\n</td>\n</tr>\n";
	}
}