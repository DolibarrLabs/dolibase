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

dolibase_include_once('/core/class/form_page.php');

/**
 * SetupPage class
 */

class SetupPage extends FormPage
{
	/**
	 * @var boolean used to colorise options rows (odd | peer)
	 */
	protected $odd = true;
	/**
	 * @var string numbering model const name
	 */
	protected $num_model_const_name;
	

	/**
	 * Constructor
	 * 
	 * @param     $page_title     HTML page title
	 * @param     $access_perm    Access permission
	 */
	public function __construct($page_title = 'Setup', $access_perm = '$user->admin')
	{
		global $dolibase_config;

		// Set numbering model constant name
		$this->num_model_const_name = strtoupper($dolibase_config['rights_class']) . '_ADDON';

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

		parent::__construct($page_title, $access_perm);
	}

	/**
	 * Load default actions
	 *
	 */
	protected function loadDefaultActions()
	{
		global $conf, $db, $langs, $dolibase_config;

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

		else if ($action == 'updateMask')
		{
			$maskconst = GETPOST('maskconst','alpha');
			$mask = GETPOST('mask','alpha');

			if ($maskconst) $res = dolibarr_set_const($db, $maskconst, $mask,'chaine', 0, '', $conf->entity);

			if (! $res > 0) $error++;

		 	if (! $error)
		    {
		        setEventMessages($langs->trans("SetupSaved"), null, 'mesgs');
		    }
		    else
		    {
		        setEventMessages($langs->trans("Error"), null, 'errors');
		    }
		}

		else if ($action == 'setmod')
		{
			dolibarr_set_const($db, $this->num_model_const_name, $value, 'chaine', 0, '', $conf->entity);
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
		if (empty($this->tabs)) {
			$this->addTab("Settings", "/".$dolibase_config['module_folder']."/admin/".$dolibase_config['setup_page_url']."?mainmenu=home", true);
			$this->addTab("About", "/".$dolibase_config['module_folder']."/admin/".$dolibase_config['about_page_url']."?mainmenu=home");
		}

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

	/**
	 * Print numbering models
	 *
	 */
	public function printNumModels()
	{
		global $conf, $langs, $dolibase_config;

		$const_name = $this->num_model_const_name;

		print '<table class="noborder" width="100%">';
		print '<tr class="liste_titre">';
		print '<td>'.$langs->trans("Name").'</td>';
		print '<td>'.$langs->trans("Description").'</td>';
		print '<td class="nowrap">'.$langs->trans("Example").'</td>';
		print '<td align="center" width="60">'.$langs->trans("Status").'</td>';
		print '<td align="center" width="16">'.$langs->trans("ShortInfo").'</td>';
		print '</tr>'."\n";

		clearstatcache();

		$dirmodels = array_merge(array('/'),(array) $conf->modules_parts['models']);

		foreach ($dirmodels as $reldir)
		{
			$dir = dol_buildpath($reldir."/dolibase/core/num_models/");
			$mod_dir = dol_buildpath($reldir."/".$dolibase_config['module_folder']."/dolibase/core/num_models/");

			$dir = ! is_dir($dir) ? $mod_dir : $dir;

			if (is_dir($dir))
			{
				$handle = opendir($dir);
				if (is_resource($handle))
				{
					$var = true;

					while (($file = readdir($handle))!==false)
					{
						if (substr($file, dol_strlen($file)-3, 3) == 'php')
						{
							$file = substr($file, 0, dol_strlen($file)-4);

							require_once $dir.$file.'.php';

							$classname = 'NumModel'.ucfirst($file);

							$module = new $classname();

							// Show modules according to features level
							if ($module->version == 'development'  && $conf->global->MAIN_FEATURES_LEVEL < 2) continue;
							if ($module->version == 'experimental' && $conf->global->MAIN_FEATURES_LEVEL < 1) continue;

							if ($module->isEnabled())
							{
								$var=!$var;
								print '<tr '.$bc[$var].'><td>'.$module->nom."</td><td>\n";
								print $module->info();
								print '</td>';

								// Show example of numbering model
								print '<td class="nowrap">';
								$tmp=$module->getExample();
								if (preg_match('/^Error/',$tmp)) print '<div class="error">'.$langs->trans($tmp).'</div>';
								elseif ($tmp=='NotConfigured') print $langs->trans($tmp);
								else print $tmp;
								print '</td>'."\n";

								print '<td align="center">';
								if ($conf->global->$const_name == $file)
								{
									print img_picto($langs->trans("Activated"),'switch_on');
								}
								else
								{
									print '<a href="'.$_SERVER["PHP_SELF"].'?action=setmod&amp;value='.$file.'">';
									print img_picto($langs->trans("Disabled"),'switch_off');
									print '</a>';
								}
								print '</td>';

								// Info
								$htmltooltip='';
								$htmltooltip.=''.$langs->trans("Version").': <b>'.$module->getVersion().'</b><br>';
								$nextval=$module->getNextValue();
								if ("$nextval" != $langs->trans("NotAvailable")) {  // Keep " on nextval
									$htmltooltip.=''.$langs->trans("NextValue").': ';
									if ($nextval) {
										if (preg_match('/^Error/',$nextval) || $nextval=='NotConfigured') {
											$nextval = $langs->trans($nextval);
										}
										$htmltooltip.=$nextval.'<br>';
									} else {
										$htmltooltip.=$langs->trans($module->error).'<br>';
									}
								}

								print '<td align="center">';
								print $this->form->textwithpicto('',$htmltooltip,1,0);
								print '</td>';

								print "</tr>\n";
							}
						}
					}
					closedir($handle);
				}
			}
		}
		print "</table><br>\n";
	}
}