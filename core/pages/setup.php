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

dolibase_include_once('core/class/form_page.php');
require_once DOL_DOCUMENT_ROOT . '/core/lib/admin.lib.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/ajax.lib.php';

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
	 * @var string const name prefix
	 */
	protected $const_name_prefix;
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
	 * @var string document model preview picture
	 */
	protected $doc_model_preview_picture = '';
	/**
	 * @var string used to generate documents specimen
	 */
	protected $doc_object_class;
	/**
	 * @var string used to generate documents specimen
	 */
	protected $doc_object_path;
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
	 * @var boolean used to add changelog tab
	 */
	protected $add_changelog_tab = false;
	/**
	 * @var boolean used to enable/disable ajax for switch options
	 */
	protected $use_ajax_to_switch_on_off = false;


	/**
	 * Constructor
	 * 
	 * @param     $page_title                 HTML page title
	 * @param     $access_perm                Access permission
	 * @param     $disable_default_actions    Disable default actions
	 * @param     $add_extrafields_tab        Add extrafields tab
	 * @param     $add_changelog_tab          Add changelog tab
	 * @param     $const_name_prefix          Constant name prefix
	 * @param     $doc_model_type             Document model type
	 * @param     $doc_object_class           Document object class
	 * @param     $doc_object_path            Document object path
	 */
	public function __construct($page_title = 'Setup', $access_perm = '$user->admin', $disable_default_actions = false, $add_extrafields_tab = false, $add_changelog_tab = false, $const_name_prefix = '', $doc_model_type = '', $doc_object_class = '', $doc_object_path = '')
	{
		global $langs, $dolibase_config;

		// Load lang files
		$langs->load("admin");
		$langs->load("setup_page@".$dolibase_config['langs']['path']);

		// Set attributes
		$this->disable_default_actions = $disable_default_actions;
		$this->add_extrafields_tab     = $add_extrafields_tab;
		$this->add_changelog_tab       = $add_changelog_tab;
		$this->const_name_prefix       = (! empty($const_name_prefix) ? $const_name_prefix : get_rights_class(true));

		// Set numbering model constant name
		$this->num_model_const_name = $this->const_name_prefix . '_ADDON';

		// Set document model constant name, type, object class & path
		$this->doc_model_const_name = $this->const_name_prefix . '_ADDON_PDF';
		$this->doc_model_type       = (! empty($doc_model_type) ? $doc_model_type : get_rights_class());
		$this->doc_object_class     = $doc_object_class;
		$this->doc_object_path      = $doc_object_path;

		// Add some custom css
		$this->appendToHead('<link rel="stylesheet" type="text/css" href="'.dolibase_buildurl('core/css/setup.css.php').'">'."\n");

		parent::__construct($page_title, $access_perm);
	}

	/**
	 * Set Title link
	 *
	 * @param    $link       Link href
	 * @param    $label      Link label
	 * @param    $enable     Condition to enable
	 * @return   $this
	 */
	public function setTitleLink($link, $label, $enable = '$user->admin')
	{
		global $langs;

		if (empty($enable) || verifCond($enable)) {
			$this->title_link = '<a href="'.$link.'">'.$langs->trans($label).'</a>';
		}

		return $this;
	}

	/**
	 * Set Document model(s) preview picture
	 *
	 * @param    $picture     Document model preview picture
	 * @return   $this
	 */
	public function setDocModelPreviewPicture($picture)
	{
		$this->doc_model_preview_picture = $picture;

		return $this;
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
			if (preg_match('/^set_(.*)/', $action, $reg))
			{
				$code = $reg[1];
				$value = (is_submitted($code) ? GETPOST($code) : 1);

				if (dolibarr_set_const($db, $code, $value, 'chaine', 0, '', $conf->entity) > 0)
				{
					dolibase_redirect($_SERVER["PHP_SELF"].'?mainmenu=home');
				}
				else
				{
					dol_print_error($db);
				}
			}

			else if (preg_match('/^del_(.*)/', $action, $reg))
			{
				$code = $reg[1];

				if (dolibarr_del_const($db, $code, $conf->entity) > 0)
				{
					dolibase_redirect($_SERVER["PHP_SELF"].'?mainmenu=home');
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
				$error = 0;

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
				$ret = delDocumentModel($value, $this->doc_model_type);

				if ($ret > 0 && $conf->global->{$this->doc_model_const_name} == $value)
				{
					dolibarr_del_const($db, $this->doc_model_const_name, $conf->entity);
				}
			}

			// Set default document model
			else if ($action == 'setdefaultdoc')
			{
				$value = GETPOST('value', 'alpha');

				if (dolibarr_set_const($db, $this->doc_model_const_name, $value, 'chaine', 0, '', $conf->entity))
				{
					// The constant that was read before the new set
					// We therefore requires a variable to have a coherent view
					$conf->global->{$this->doc_model_const_name} = $value;
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

				if (! empty($this->doc_object_path) && ! empty($this->doc_object_class)) {
					dol_include_once($this->doc_object_path);
					$classname = $this->doc_object_class;
				}
				else {
					dolibase_include_once('core/class/custom_object.php');
					$classname = 'CustomObject';
				}

				$object = new $classname($db);

				if (method_exists($object, 'initAsSpecimen'))
				{
					$object->initAsSpecimen();
				}
				else
				{
					$object->doc_title     = 'SPECIMEN';
					$object->ref           = 'SPECIMEN';
					$object->specimen      = 1;
					$object->creation_date = time();
					$object->doc_lines     = array(
						array('name' => 'Lorem ipsum', 'value' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit.'),
						array('name' => 'Lorem ipsum', 'value' => 'Aliquam tincidunt mauris eu risus.'),
						array('name' => 'Lorem ipsum', 'value' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio. Quisque volutpat mattis eros. Nullam malesuada erat ut turpis. Suspendisse urna nibh, viverra non, semper suscipit, posuere a, pede.')
					);
				}

				// Search template files
				$dirmodels = array(
					dolibase_buildpath("core/doc_models/"),
					dol_buildpath($dolibase_config['module']['folder']."/core/doc_models/")
				);
				$error = 0;

				foreach ($dirmodels as $dir)
				{
					$file = $dir."pdf_".$model.".modules.php";
					if (file_exists($file))
					{
						$error = 0;
						require_once $file;

						$classname = 'pdf_'.$model;
						$module = new $classname($db);

						// Generate document
						if ($module->write_file($object, $langs) > 0)
						{
							dolibase_redirect(DOL_URL_ROOT."/document.php?modulepart=".$this->modulepart."&file=SPECIMEN.pdf");
						}
						else
						{
							setEventMessages($module->error, null, 'errors');
							dol_syslog($module->error, LOG_ERR);
						}

						break;
					}
					else
					{
						$error++;
					}
				}

				if ($error)
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
			$this->addTab("Settings", $dolibase_config['module']['folder']."/admin/".$dolibase_config['other']['setup_page']."?mainmenu=home", true);
			if ($this->add_extrafields_tab) {
				$this->addTab("ExtraFields", $dolibase_config['module']['folder']."/admin/extrafields.php?mainmenu=home");
			}
			if ($this->add_changelog_tab) {
				$this->addTab("Changelog", $dolibase_config['module']['folder']."/admin/changelog.php?mainmenu=home");
			}
			$this->addTab("About", $dolibase_config['module']['folder']."/admin/".$dolibase_config['other']['about_page']."?mainmenu=home");
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
	 * Set $use_ajax_to_switch_on_off attribute to true
	 *
	 * @return   $this
	 */
	public function useAjaxToSwitchOnOff()
	{
		$this->use_ajax_to_switch_on_off = true;

		return $this;
	}

	/**
	 * Show setup_not_available template (only once)
	 *
	 * @return   $this
	 */
	public function setupNotAvailable()
	{
		$template_path = dolibase_buildpath('core/tpl/setup_not_available.php');

		$this->showTemplate($template_path, true, true);

		return $this;
	}

	/**
	 * Create a new table for options
	 *
	 * @param     $first_column_name     First column name
	 * @return    $this
	 */
	public function newOptionsTable($first_column_name = 'Option')
	{
		$options_table_cols = array(
								array('name' => $first_column_name),
								array('name' => 'Value', 'attr' => 'align="center" width="100"')
							);

		$this->openTable($options_table_cols);

		return $this;
	}

	/**
	 * Add a new option
	 *
	 * @param     $option_desc       Option description
	 * @param     $option_content    Option content, it can be HTML or even a string
	 * @param     $const_name        Option constant name
	 * @param     $morehtmlright     more HTML to add on the right of the option description
	 * @param     $width             Option last column/td width
	 * @param     $form_enctype      Form enctype attribute
	 * @return    $this
	 */
	public function addOption($option_desc, $option_content, $const_name = '', $morehtmlright = '', $width = 300, $form_enctype = '')
	{
		global $conf, $langs, $bc;

		$this->odd = !$this->odd;

		echo '<tr '.$bc[$this->odd].'><td>'.$langs->trans($option_desc).$morehtmlright.'</td>'."\n";
		echo '<td width="'.$width.'" align="right">'."\n";
		if (! empty($const_name)) {
			echo '<form action="'.$_SERVER["PHP_SELF"].'" method="POST"'.(! empty($form_enctype) ? ' enctype="'.$form_enctype.'"' : '').">\n";
			echo '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'" />'."\n";
			echo '<input type="hidden" name="action" value="set_'.$const_name.'" />'."\n";
		}
		echo $option_content."\n";
		if (! empty($const_name)) {
			echo '&nbsp;&nbsp;<input type="submit" class="button" value="'.$langs->trans("Modify").'">&nbsp;&nbsp;'."\n";
		}
		echo "</form>\n</td>\n</tr>\n";

		return $this;
	}

	/**
	 * Add a new switch option
	 *
	 * @param     $option_desc       Option description
	 * @param     $const_name        Option constant name
	 * @param     $disabled          disable option or not
	 * @param     $morehtmlright     more HTML to add on the right of the option description
	 * @return    $this
	 */
	public function addSwitchOption($option_desc, $const_name, $disabled = false, $morehtmlright = '')
	{
		global $conf, $langs, $bc;

		$this->odd = !$this->odd;
		$more_attr = $disabled ? ' class="disabled nopointerevents"' : '';

		echo '<tr '.$bc[$this->odd].'><td'.$more_attr.'>'.$langs->trans($option_desc).$morehtmlright.'</td>'."\n";
		echo '<td'.$more_attr.' align="right">'."\n";
		if ($this->use_ajax_to_switch_on_off && ! empty($conf->use_javascript_ajax) && function_exists('ajax_constantonoff'))
		{
			echo ajax_constantonoff($const_name);
		}
		else
		{
			if (empty($conf->global->$const_name))
			{
				echo '<a href="'.$_SERVER['PHP_SELF'].'?action=set_'.$const_name.'">'.img_picto($langs->trans("Disabled"), 'switch_off').'</a>'."\n";
			}
			else
			{
				echo '<a href="'.$_SERVER['PHP_SELF'].'?action=del_'.$const_name.'">'.img_picto($langs->trans("Enabled"), 'switch_on').'</a>'."\n";
			}
		}
		echo "&nbsp;&nbsp;&nbsp;&nbsp;</td>\n</tr>\n";

		return $this;
	}

	/**
	 * Add a new text option
	 *
	 * @param     $option_desc       Option description
	 * @param     $const_name        Option constant name
	 * @param     $morehtmlright     more HTML to add on the right of the option description
	 * @param     $size              Option textbox size
	 * @param     $width             Option last column/td width
	 * @return    $this
	 */
	public function addTextOption($option_desc, $const_name, $morehtmlright = '', $size = 16, $width = 300)
	{
		global $conf;

		$option_content = $this->form->textInput($const_name, $conf->global->$const_name, $size);

		$this->addOption($option_desc, $option_content, $const_name, $morehtmlright, $width);

		return $this;
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
	 * @return    $this
	 */
	public function addNumberOption($option_desc, $const_name, $min = 0, $max = 100, $morehtmlright = '', $width = 300)
	{
		global $conf;

		$option_content = $this->form->numberInput($const_name, $conf->global->$const_name, $min, $max);

		$this->addOption($option_desc, $option_content, $const_name, $morehtmlright, $width);

		return $this;
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
	 * @return    $this
	 */
	public function addRangeOption($option_desc, $const_name, $min = 0, $max = 100, $morehtmlright = '', $width = 300)
	{
		global $conf;

		$option_content = $this->form->rangeInput($const_name, $conf->global->$const_name, $min, $max);

		$this->addOption($option_desc, $option_content, $const_name, $morehtmlright, $width);

		return $this;
	}

	/**
	 * Add a new list option
	 *
	 * @param     $option_desc       Option description
	 * @param     $const_name        Option constant name
	 * @param     $list              Options list array
	 * @param     $morehtmlright     more HTML to add on the right of the option description
	 * @param     $width             Option last column/td width
	 * @return    $this
	 */
	public function addListOption($option_desc, $const_name, $list, $morehtmlright = '', $width = 300)
	{
		global $conf;

		$option_content = $this->form->listInput($const_name, $list, $conf->global->$const_name);

		$this->addOption($option_desc, $option_content, $const_name, $morehtmlright, $width);

		return $this;
	}

	/**
	 * Add a new color picker option
	 *
	 * @param     $option_desc       Option description
	 * @param     $const_name        Option constant name
	 * @param     $morehtmlright     more HTML to add on the right of the option description
	 * @param     $width             Option last column/td width
	 * @return    $this
	 */
	public function addColorOption($option_desc, $const_name, $morehtmlright = '', $width = 300)
	{
		global $conf;

		$option_content = $this->form->colorInput($const_name, $conf->global->$const_name);

		$this->addOption($option_desc, $option_content, $const_name, $morehtmlright, $width);

		return $this;
	}

	/**
	 * Add a button to the page
	 *
	 * @param     $name                 button name
	 * @param     $href                 button href
	 * @param     $target               button target
	 * @param     $class                button class
	 * @param     $close_parent_div     should close parent div or not
	 * @return    $this
	 */
	public function addButton($name, $href = '#', $target = '_self', $class = 'butAction', $close_parent_div = false)
	{
		global $langs;

		if (! $this->close_buttons_div) {
			dol_fiche_end();
			echo '<div class="tabsAction force-center">';
			$this->close_buttons_div = true;
		}

		echo '<a class="'.$class.'" href="'.$href.'" target="'.$target.'">'.$langs->trans($name).'</a>';

		if ($close_parent_div) {
			echo '</div>';
			$this->close_buttons_div = false;
		}

		return $this;
	}

	/**
	 * Print numbering models
	 *
	 * @param     $model_name     Numbering model name
	 * @return    $this
	 */
	public function printNumModels($model_name = '')
	{
		global $conf, $langs, $dolibase_config;

		echo '<table class="noborder" width="100%">';
		echo '<tr class="liste_titre">';
		echo '<td>'.$langs->trans("Name").'</td>';
		echo '<td>'.$langs->trans("Description").'</td>';
		echo '<td class="nowrap">'.$langs->trans("Example").'</td>';
		echo '<td align="center" width="60">'.$langs->trans("Status").'</td>';
		echo '<td align="center" width="16">'.$langs->trans("ShortInfo").'</td>';
		echo '</tr>'."\n";

		clearstatcache();

		$dirmodels = array(
			dolibase_buildpath("core/num_models/"),
			dol_buildpath($dolibase_config['module']['folder']."/core/num_models/")
		);

		foreach ($dirmodels as $dir)
		{
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

							$model = new $classname($this->const_name_prefix, $model_name);

							// Show models according to features level
							if ($model->version == 'development'  && $conf->global->MAIN_FEATURES_LEVEL < 2) continue;
							if ($model->version == 'experimental' && $conf->global->MAIN_FEATURES_LEVEL < 1) continue;

							if ($model->isEnabled())
							{
								$var = !$var;
								echo '<tr '.$bc[$var].'><td>'.$model->nom."</td><td>\n";
								echo $model->info();
								echo '</td>';

								// Show example of numbering model
								echo '<td class="nowrap">';
								$tmp = $model->getExample();
								if (preg_match('/^Error/',$tmp)) echo '<div class="error">'.$langs->trans($tmp).'</div>';
								elseif ($tmp == 'NotConfigured') echo $langs->trans($tmp);
								else echo $tmp;
								echo '</td>'."\n";

								echo '<td align="center">';
								if ($conf->global->{$this->num_model_const_name} == $file)
								{
									echo img_picto($langs->trans("Activated"), 'switch_on');
								}
								else
								{
									echo '<a href="'.$_SERVER["PHP_SELF"].'?action=setmod&amp;value='.$file.'">';
									echo img_picto($langs->trans("Disabled"), 'switch_off');
									echo '</a>';
								}
								echo '</td>';

								// Info
								$htmltooltip = $langs->trans("Version").': <b>'.$model->getVersion().'</b><br>';
								$nextval = $model->getNextValue();
								if ("$nextval" != $langs->trans("NotAvailable")) {  // Keep " on nextval
									$htmltooltip.= $langs->trans("NextValue").': ';
									if ($nextval) {
										if (preg_match('/^Error/',$nextval) || $nextval == 'NotConfigured') {
											$nextval = $langs->trans($nextval);
										}
										$htmltooltip.= $nextval.'<br>';
									} else {
										$htmltooltip.= $langs->trans($model->error).'<br>';
									}
								}

								echo '<td align="center">';
								echo $this->form->textwithpicto('', $htmltooltip, 1, 0);
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

		return $this;
	}

	/**
	 * Print document models
	 *
	 * @return    $this
	 */
	public function printDocModels()
	{
		global $db, $conf, $langs, $dolibase_config;

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

		$dirmodels = array(
			dolibase_buildpath("core/doc_models/"),
			dol_buildpath($dolibase_config['module']['folder']."/core/doc_models/")
		);

		foreach ($dirmodels as $dir)
		{
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
								$var = !$var;
								echo '<tr '.$bc[$var].'><td width="100">'.$model->name."</td><td>\n";
								if (method_exists($model, 'info')) echo $model->info($langs);
								else echo $model->description;
								echo '</td>';

								// Active
								if (in_array($model->name, $def))
								{
									echo '<td align="center">'."\n";
									echo '<a href="'.$_SERVER["PHP_SELF"].'?action=deldoc&value='.$model->name.'">';
									echo img_picto($langs->trans("Enabled"), 'switch_on');
									echo '</a>';
									echo '</td>';
								}
								else
								{
									echo '<td align="center">'."\n";
									echo '<a href="'.$_SERVER["PHP_SELF"].'?action=setdoc&value='.$model->name.'">'.img_picto($langs->trans("Disabled"), 'switch_off').'</a>';
									echo "</td>";
								}

								// Default
								echo '<td align="center">';
								if ($conf->global->{$this->doc_model_const_name} == $model->name)
								{
									echo img_picto($langs->trans("Default"), 'on');
								}
								else
								{
									echo '<a href="'.$_SERVER["PHP_SELF"].'?action=setdefaultdoc&value='.$model->name.'" alt="'.$langs->trans("Default").'">'.img_picto($langs->trans("Disabled"),'off').'</a>';
								}
								echo '</td>';

								// Info
								$htmltooltip = $langs->trans("Name").': '.$model->name;
								$htmltooltip.= '<br>'.$langs->trans("Type").': '.($model->type?$model->type:$langs->trans("Unknown"));
								if ($model->type == 'pdf')
								{
									$htmltooltip.= '<br>'.$langs->trans("Width").'/'.$langs->trans("Height").': '.$model->page_largeur.'/'.$model->page_hauteur;
								}
								$htmltooltip.= '<br><br><u>'.$langs->trans("FeaturesSupported").':</u>';
								$htmltooltip.= '<br>'.$langs->trans("Logo").': '.yn($model->option_logo, 1, 1);
								$htmltooltip.= '<br>'.$langs->trans("MultiLanguage").': '.yn($model->option_multilang, 1, 1);
								$htmltooltip.= '<br>'.$langs->trans("WatermarkOnDraft").': '.yn($model->option_draft_watermark, 1, 1);

								echo '<td align="center">';
								echo $this->form->textwithpicto('', $htmltooltip, 1, 0);
								echo '</td>';

								// Preview
								echo '<td align="center">';
								if ($model->type == 'pdf')
								{
									$picto = (! empty($this->doc_model_preview_picture) ? $this->doc_model_preview_picture : $dolibase_config['module']['picture'].'@'.$dolibase_config['module']['folder']);
									echo '<a href="'.$_SERVER["PHP_SELF"].'?action=specimen&model='.$model->name.'">'.img_object($langs->trans("Preview"), $picto).'</a>';
								}
								else
								{
									echo img_object($langs->trans("PreviewNotAvailable"), 'generic');
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

		return $this;
	}
}
