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
	 * @var string used to disable default actions
	 */
	protected $disable_default_actions = false;
	/**
	 * @var string Title right link
	 */
	protected $title_link = '';


	/**
	 * Constructor
	 * 
	 * @param     $page_title    			  HTML page title
	 * @param     $access_perm   			  Access permission
	 * @param     $disable_default_actions    Disable default actions
	 */
	public function __construct($page_title = 'Setup', $access_perm = '$user->admin', $disable_default_actions = false)
	{
		global $langs, $dolibase_config;

		// Load lang files
		$langs->load("admin");
		$langs->load("setup_page@".$dolibase_config['module']['folder']);

		// Set attributes
		$this->disable_default_actions = $disable_default_actions;

		// Set numbering model constant name
		$this->num_model_const_name = strtoupper($dolibase_config['module']['rights_class']) . '_ADDON';

		// Add some custom css
		$this->head.= "<style>
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
	 * Set Title link
	 *
	 * @param    $link       Link href
	 * @param    $label      Link label
	 * @param    $enable     Condition to enable
	 */
	public function setTitleLink($link, $label, $enable = '$user->admin')
	{
		global $langs;

		if (empty($enable) || verifCond($enable)) {
			$this->title_link = '<a href="'.$link.'">'.$langs->trans($label).'</a>';
		}
	}

	/**
	 * Load default actions
	 *
	 */
	protected function loadDefaultActions()
	{
		if (! $this->disable_default_actions)
		{
			global $conf, $db, $langs, $dolibase_config;

			// Libraries
			require_once DOL_DOCUMENT_ROOT . "/core/lib/admin.lib.php";

			// Parameters
			$action = GETPOST('action', 'alpha');

			// Actions
			if (preg_match('/set_(.*)/', $action, $reg))
			{
				$code = $reg[1];
				$value = GETPOST($code);

				if (dolibarr_set_const($db, $code, $value, 'chaine', 0, '', $conf->entity) > 0)
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
				$value = GETPOST('value', 'alpha');

				dolibarr_set_const($db, $this->num_model_const_name, $value, 'chaine', 0, '', $conf->entity);
			}
		}
	}

	/**
	 * Generate page body
	 *
	 */
	protected function generate()
	{
		global $user, $langs, $dolibase_config;

		// Add sub title
		if (empty($this->title_link) && $user->admin) {
			$this->title_link = '<a href="'.DOL_URL_ROOT.'/admin/modules.php?mainmenu=home">'.$langs->trans("BackToModuleList").'</a>';
		}
		$this->addSubTitle($this->title, 'title_setup.png', $this->title_link);

		// Add default tabs
		if (empty($this->tabs)) {
			$this->addTab("Settings", "/".$dolibase_config['module']['folder']."/admin/".$dolibase_config['other']['setup_page']."?mainmenu=home", true);
			$this->addTab("About", "/".$dolibase_config['module']['folder']."/admin/".$dolibase_config['other']['about_page']."?mainmenu=home");
		}
		
		parent::generate();
	}

	/**
	 * Create a new table for options
	 *
	 * @param     $first_column_name     First column name
	 */
	public function newOptionsTable($first_column_name = 'Option')
	{
		$options_table_cols = array(
								array('name' => $first_column_name, 'attr' => ''),
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

		echo '<tr '.$bc[$this->odd].'><td>'.$langs->trans($option_desc).$morehtmlright.'</td>'."\n";
		echo '<td align="center">&nbsp;</td>'."\n";
		echo '<td width="'.$width.'" align="right">'."\n";
		if (! empty($const_name)) {
			echo '<form action="'.$_SERVER["PHP_SELF"].'" method="POST">'."\n";
			echo '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'" />'."\n";
			echo '<input type="hidden" name="action" value="set_'.$const_name.'" />'."\n";
		}
		echo $option_content."\n";
		if (! empty($const_name)) {
			echo '&nbsp;&nbsp;<input type="submit" class="button" value="'.$langs->trans("Modify").'">&nbsp;&nbsp;'."\n";
		}
		echo "</form>\n</td>\n</tr>\n";
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

		echo '<tr '.$bc[$this->odd].'><td'.$more_attr.'>'.$langs->trans($option_desc).$morehtmlright.'</td>'."\n";
		echo '<td'.$more_attr.' align="center">&nbsp;</td>'."\n";
		echo '<td'.$more_attr.' align="right">'."\n";
		if (empty($conf->global->$const_name))
		{
		    echo '<a href="'.$_SERVER['PHP_SELF'].'?action=set_'.$const_name.'&amp;'.$const_name.'=1">'.img_picto($langs->trans("Disabled"),'switch_off').'</a>'."\n";
		}
		else
		{
		    echo '<a href="'.$_SERVER['PHP_SELF'].'?action=set_'.$const_name.'&amp;'.$const_name.'=0">'.img_picto($langs->trans("Enabled"),'switch_on').'</a>'."\n";
		}
		echo "&nbsp;&nbsp;&nbsp;&nbsp;</td>\n</tr>\n";
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
		global $conf;

		$option_content = $this->form->textInput($const_name, $conf->global->$const_name, $size);

		$this->addOption($option_desc, $option_content, $const_name, $morehtmlright, $width);
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
		global $conf;

		$option_content = $this->form->numberInput($const_name, $conf->global->$const_name, $min, $max);

		$this->addOption($option_desc, $option_content, $const_name, $morehtmlright, $width);
	}

	/**
	 * Add a new range option
	 *
	 * @param     $option_desc       Option description
	 * @param     $const_name        Option constant name
	 * @param     $min               Option minimum value
	 * @param     $max               Option maximum value
	 * @param     $morehtmlright     more HTML to add on the right of the option description
	 * @param     $width             Option last column/td width
	 */
	public function addRangeOption($option_desc, $const_name, $min = 0, $max = 100, $morehtmlright = '', $width = 250)
	{
		global $conf;

		$option_content = $this->form->rangeInput($const_name, $conf->global->$const_name, $min, $max);

		$this->addOption($option_desc, $option_content, $const_name, $morehtmlright, $width);
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
		global $conf;

		$option_content = $this->form->listInput($const_name, $list, $conf->global->$const_name);

		$this->addOption($option_desc, $option_content, $const_name, $morehtmlright, $width);
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
		global $conf;

		$option_content = $this->form->colorInput($const_name, $conf->global->$const_name);

		$this->addOption($option_desc, $option_content, $const_name, $morehtmlright, $width);
	}

	/**
	 * Add a button to the page
	 *
	 * @param     $name                 button name
	 * @param     $href                 button href
	 * @param     $target               button target
	 * @param     $class                button class
	 * @param     $close_parent_div     should close parent div or not
	 */
	public function addButton($name, $href = '#', $target = '_self', $class = 'butAction', $close_parent_div = false)
	{
		global $langs;

		if (! $this->close_buttons_div) {
			dol_fiche_end();
			echo '<div class="tabsAction" style="text-align: center;">';
			$this->close_buttons_div = true;
		}

		echo '<a class="'.$class.'" href="'.$href.'" target="'.$target.'">'.$langs->trans($name).'</a>';

		if ($close_parent_div) {
			echo '</div>';
			$this->close_buttons_div = false;
		}
	}

	/**
	 * Print numbering models
	 *
	 */
	public function printNumModels()
	{
		global $conf, $langs, $dolibase_config;

		$const_name = $this->num_model_const_name;

		echo '<table class="noborder" width="100%">';
		echo '<tr class="liste_titre">';
		echo '<td>'.$langs->trans("Name").'</td>';
		echo '<td>'.$langs->trans("Description").'</td>';
		echo '<td class="nowrap">'.$langs->trans("Example").'</td>';
		echo '<td align="center" width="60">'.$langs->trans("Status").'</td>';
		echo '<td align="center" width="16">'.$langs->trans("ShortInfo").'</td>';
		echo '</tr>'."\n";

		clearstatcache();

		$dirmodels = array_merge(array('/'),(array) $conf->modules_parts['models']);

		foreach ($dirmodels as $reldir)
		{
			$dir = dol_buildpath($reldir."/dolibase/core/num_models/");
			$mod_dir = dol_buildpath($reldir."/".$dolibase_config['module']['folder']."/dolibase/core/num_models/");

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
								echo '<tr '.$bc[$var].'><td>'.$module->nom."</td><td>\n";
								echo $module->info();
								echo '</td>';

								// Show example of numbering model
								echo '<td class="nowrap">';
								$tmp=$module->getExample();
								if (preg_match('/^Error/',$tmp)) echo '<div class="error">'.$langs->trans($tmp).'</div>';
								elseif ($tmp=='NotConfigured') echo $langs->trans($tmp);
								else echo $tmp;
								echo '</td>'."\n";

								echo '<td align="center">';
								if ($conf->global->$const_name == $file)
								{
									echo img_picto($langs->trans("Activated"),'switch_on');
								}
								else
								{
									echo '<a href="'.$_SERVER["PHP_SELF"].'?action=setmod&amp;value='.$file.'">';
									echo img_picto($langs->trans("Disabled"),'switch_off');
									echo '</a>';
								}
								echo '</td>';

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

								echo '<td align="center">';
								echo $this->form->textwithpicto('',$htmltooltip,1,0);
								echo '</td>';

								echo "</tr>\n";
							}
						}
					}
					closedir($handle);
				}
			}
		}
		echo "</table><br>\n";
	}
}