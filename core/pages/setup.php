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
	 * @var string document model const name
	 */
	protected $doc_model_const_name;
	/**
	 * @var string document model type
	 */
	protected $doc_model_type;
	/**
	 * @var boolean used to disable default actions
	 */
	protected $disable_default_actions = false;
	/**
	 * @var string Title right link
	 */
	protected $title_link = '';
	/**
	 * @var boolean used to add extrafields tab
	 */
	protected $add_extrafields_tab = false;


	/**
	 * Constructor
	 * 
	 * @param     $page_title    			  HTML page title
	 * @param     $access_perm   			  Access permission
	 * @param     $disable_default_actions    Disable default actions
	 * @param     $add_extrafields_tab        Add extrafields tab
	 */
	public function __construct($page_title = 'Setup', $access_perm = '$user->admin', $disable_default_actions = false, $add_extrafields_tab = false)
	{
		global $langs, $dolibase_config;

		// Load lang files
		$langs->load("admin");
		$langs->load("setup_page@".$dolibase_config['module']['folder']);

		// Set attributes
		$this->disable_default_actions = $disable_default_actions;
		$this->add_extrafields_tab     = $add_extrafields_tab;

		// Set numbering model constant name
		$this->num_model_const_name = get_rights_class(true) . '_ADDON';

		// Set document model constant name & type
		$this->doc_model_const_name = get_rights_class(true) . '_ADDON_PDF';
		$this->doc_model_type       = get_rights_class();

		// Add some custom css
		$this->appendToHead('<link rel="stylesheet" type="text/css" href="'.DOL_URL_ROOT.DOLIBASE_PATH.'/core/css/setup.css.php">'."\n");

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

			// Activate a document model
			else if ($action == 'setdoc')
			{
				$value = GETPOST('value', 'alpha');

				$ret = addDocumentModel($value, $this->doc_model_type);
			}

			// Disable a document model
			else if ($action == 'deldoc')
			{
				$value = GETPOST('value', 'alpha');
				$const_name = $this->doc_model_const_name;

				$ret = delDocumentModel($value, $this->doc_model_type);
				if ($ret > 0)
				{
					if ($conf->global->$const_name == $value) dolibarr_del_const($db, $const_name, $conf->entity);
				}
			}

			// Set default document model
			else if ($action == 'setdefaultdoc')
			{
				$value = GETPOST('value', 'alpha');
				$const_name = $this->doc_model_const_name;

				if (dolibarr_set_const($db, $const_name, $value, 'chaine', 0, '', $conf->entity))
				{
					// The constant that was read before the new set
					// We therefore requires a variable to have a coherent view
					$conf->global->$const_name = $value;
				}

				// activate model
				$ret = delDocumentModel($value, $this->doc_model_type);
				if ($ret > 0)
				{
					$ret = addDocumentModel($value, $this->doc_model_type);
				}
			}

			// specimen
			else if ($action == 'specimen')
			{
				$model = GETPOST('model','alpha');

				dolibase_include_once('/core/class/custom_object.php');

				$object                = new CustomObject($db);
				//$object->documentTitle = 'SPECIMEN';
				$object->ref           = 'SPECIMEN';
				$object->specimen      = 1;
				$object->creation_date = time();
				$object->lines         = array(
					array('name' => 'Lorem ipsum', 'value' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit.'),
					array('name' => 'Lorem ipsum', 'value' => 'Aliquam tincidunt mauris eu risus.'),
					array('name' => 'Lorem ipsum', 'value' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio. Quisque volutpat mattis eros. Nullam malesuada erat ut turpis. Suspendisse urna nibh, viverra non, semper suscipit, posuere a, pede.')
				);

				// Search template files
				$file      = '';
				$classname = '';
				$filefound = 0;
				$dirmodels = array_merge(array('/'),(array) $conf->modules_parts['models']);
				foreach($dirmodels as $reldir)
				{
				    $file = dol_buildpath($reldir."/dolibase/core/doc_models/pdf_".$model.".modules.php", 0);
					if (file_exists($file))
					{
						$filefound = 1;
						$classname = 'pdf_'.$model;
						break;
					}
				}

				if ($filefound)
				{
					require_once $file;

					$module = new $classname($db);

					if ($module->write_file($object, $langs) > 0)
					{
						$modulepart = get_rights_class(false, true);
						header("Location: ".DOL_URL_ROOT."/document.php?modulepart=".$modulepart."&file=SPECIMEN.pdf");
						return;
					}
					else
					{
						setEventMessages($module->error, null, 'errors');
						dol_syslog($module->error, LOG_ERR);
					}
				}
				else
				{
					setEventMessages($langs->trans("ErrorModuleNotFound"), null, 'errors');
					dol_syslog($langs->trans("ErrorModuleNotFound"), LOG_ERR);
				}
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
			if ($this->add_extrafields_tab) {
				$this->addTab("ExtraFields", "/".$dolibase_config['module']['folder']."/admin/extrafields.php?mainmenu=home");
			}
			$this->addTab("About", "/".$dolibase_config['module']['folder']."/admin/".$dolibase_config['other']['about_page']."?mainmenu=home");
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

		$dirmodels = array_merge(array('/'), (array) $conf->modules_parts['models']);

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

					while (($file = readdir($handle)) !== false)
					{
						if (substr($file, dol_strlen($file)-3, 3) == 'php')
						{
							$file = substr($file, 0, dol_strlen($file)-4);

							require_once $dir.$file.'.php';

							$classname = 'NumModel'.ucfirst($file);

							$model = new $classname();

							// Show models according to features level
							if ($model->version == 'development'  && $conf->global->MAIN_FEATURES_LEVEL < 2) continue;
							if ($model->version == 'experimental' && $conf->global->MAIN_FEATURES_LEVEL < 1) continue;

							if ($model->isEnabled())
							{
								$var=!$var;
								echo '<tr '.$bc[$var].'><td>'.$model->nom."</td><td>\n";
								echo $model->info();
								echo '</td>';

								// Show example of numbering model
								echo '<td class="nowrap">';
								$tmp=$model->getExample();
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
								$htmltooltip.=''.$langs->trans("Version").': <b>'.$model->getVersion().'</b><br>';
								$nextval=$model->getNextValue();
								if ("$nextval" != $langs->trans("NotAvailable")) {  // Keep " on nextval
									$htmltooltip.=''.$langs->trans("NextValue").': ';
									if ($nextval) {
										if (preg_match('/^Error/',$nextval) || $nextval=='NotConfigured') {
											$nextval = $langs->trans($nextval);
										}
										$htmltooltip.=$nextval.'<br>';
									} else {
										$htmltooltip.=$langs->trans($model->error).'<br>';
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

	/**
	 * Print document models
	 *
	 */
	public function printDocModels()
	{
		global $db, $conf, $langs, $dolibase_config;

		$const_name = $this->doc_model_const_name;

		// Load array def with activated templates
		$def = array();
		$sql = "SELECT nom";
		$sql.= " FROM ".MAIN_DB_PREFIX."document_model";
		$sql.= " WHERE type = '".$this->doc_model_type."'";
		$sql.= " AND entity = ".$conf->entity;
		$resql = $db->query($sql);
		if ($resql)
		{
			$i = 0;
			$num_rows = $db->num_rows($resql);
			while ($i < $num_rows)
			{
				$array = $db->fetch_array($resql);
				array_push($def, $array[0]);
				$i++;
			}
		}
		else
		{
			dol_print_error($db);
		}

		echo '<table class="noborder" width="100%">';
		echo '<tr class="liste_titre">';
		echo '<td>'.$langs->trans("Name").'</td>';
		echo '<td>'.$langs->trans("Description").'</td>';
		echo '<td align="center" width="60">'.$langs->trans("Status").'</td>';
		echo '<td align="center" width="60">'.$langs->trans("Default").'</td>';
		echo '<td align="center" width="38">'.$langs->trans("ShortInfo").'</td>';
		echo '<td align="center" width="38">'.$langs->trans("Preview").'</td>';
		echo '</tr>'."\n";

		clearstatcache();

		$dirmodels = array_merge(array('/'), (array) $conf->modules_parts['models']);

		foreach ($dirmodels as $reldir)
		{
			$dir = dol_buildpath($reldir."/dolibase/core/doc_models/");
			$mod_dir = dol_buildpath($reldir."/".$dolibase_config['module']['folder']."/dolibase/core/doc_models/");

			$dir = ! is_dir($dir) ? $mod_dir : $dir;

			if (is_dir($dir))
			{
				$handle = opendir($dir);
				if (is_resource($handle))
				{
					$var = true;

					while (($file = readdir($handle)) !== false)
					{
						if (preg_match('/\.modules\.php$/i',$file) && preg_match('/^(pdf_|doc_)/',$file))
						{
							require_once $dir.$file;

							$classname = substr($file, 0, dol_strlen($file) - 12);

							$model = new $classname($db);

							// Show models according to features level
							$modelqualified = 1;
							if ($model->version == 'development'  && $conf->global->MAIN_FEATURES_LEVEL < 2) $modelqualified = 0;
							if ($model->version == 'experimental' && $conf->global->MAIN_FEATURES_LEVEL < 1) $modelqualified = 0;

							if ($modelqualified)
							{
								$var=!$var;
								echo '<tr '.$bc[$var].'><td width="100">'.$model->name."</td><td>\n";
								if (method_exists($model,'info')) echo $model->info($langs);
								else echo $model->description;
								echo '</td>';

								// Active
								if (in_array($model->name, $def))
								{
									echo '<td align="center">'."\n";
									echo '<a href="'.$_SERVER["PHP_SELF"].'?action=deldoc&value='.$model->name.'">';
									echo img_picto($langs->trans("Enabled"),'switch_on');
									echo '</a>';
									echo '</td>';
								}
								else
								{
									echo '<td align="center">'."\n";
									echo '<a href="'.$_SERVER["PHP_SELF"].'?action=setdoc&value='.$model->name.'">'.img_picto($langs->trans("Disabled"),'switch_off').'</a>';
									echo "</td>";
								}

								// Default
								echo '<td align="center">';
								if ($conf->global->$const_name == $model->name)
								{
									echo img_picto($langs->trans("Default"),'on');
								}
								else
								{
									echo '<a href="'.$_SERVER["PHP_SELF"].'?action=setdefaultdoc&value='.$model->name.'" alt="'.$langs->trans("Default").'">'.img_picto($langs->trans("Disabled"),'off').'</a>';
								}
								echo '</td>';

								// Info
								$htmltooltip =    ''.$langs->trans("Name").': '.$model->name;
								$htmltooltip.='<br>'.$langs->trans("Type").': '.($model->type?$model->type:$langs->trans("Unknown"));
								if ($model->type == 'pdf')
								{
									$htmltooltip.='<br>'.$langs->trans("Width").'/'.$langs->trans("Height").': '.$model->page_largeur.'/'.$model->page_hauteur;
								}
								$htmltooltip.='<br><br><u>'.$langs->trans("FeaturesSupported").':</u>';
								$htmltooltip.='<br>'.$langs->trans("Logo").': '.yn($model->option_logo,1,1);
								$htmltooltip.='<br>'.$langs->trans("MultiLanguage").': '.yn($model->option_multilang,1,1);
								$htmltooltip.='<br>'.$langs->trans("WatermarkOnDraft").': '.yn($model->option_draft_watermark,1,1);

								echo '<td align="center">';
								echo $this->form->textwithpicto('',$htmltooltip,1,0);
								echo '</td>';

								// Preview
								echo '<td align="center">';
								if ($model->type == 'pdf')
								{
									$picto = $dolibase_config['module']['picture'].'@'.$dolibase_config['module']['folder'];
									echo '<a href="'.$_SERVER["PHP_SELF"].'?action=specimen&model='.$model->name.'">'.img_object($langs->trans("Preview"),$picto).'</a>';
								}
								else
								{
									echo img_object($langs->trans("PreviewNotAvailable"),'generic');
								}
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