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

dolibase_include_once('core/class/crud_object.php');
dolibase_include_once('core/class/logs.php');

/**
 * CustomObject class
 */

class CustomObject extends CrudObject
{
	/**
	 * @var string Banner picture
	 */
	public $picto = ''; // e.: 'my_picture@my_module'
	/**
	 * @var array Tooltip details
	 */
	public $tooltip_details = array(); // e.: array('detail_1' => 'value_1', 'detail_2' => 'value_2')
	/**
	 * @var string Document title
	 */
	public $doc_title = '';
	/**
	 * @var array Document lines/rows
	 */
	public $doc_lines = array();
	/**
	 * @var string Card url
	 */
	public $card_url = '';
	/**
	 * @var string Module part
	 */
	protected $modulepart;


	/**
	 * Constructor
	 * 
	 */
	public function __construct()
	{
		parent::__construct();

		$this->modulepart = get_modulepart();
	}

	/**
	 * Clone an object
	 *
	 * @param  $obj  object to clone from
	 */
	public function clone($obj)
	{
		foreach (get_object_vars($obj) as $key => $value)
		{
			if (in_array($key, $this->date_fields)) {
				$this->$key = $this->db->jdate($value); // Fix error: dol_print_date function call with deprecated value of time
			}
			else {
				$this->$key = $value;
			}
		}

		// enssure that $this->id is filled because we use it in update & delete functions
		if (! in_array('id', $this->fetch_fields)) {
			$this->id = $obj->{$this->pk_name};
		}
	}

	/**
	 * Create object into database
	 *
	 * @param  array  data array, e.: array('my_field_name' => 'my_field_value', 'second_field_name' => 'second_field_value')
	 * @param  int    $notrigger 0=launch triggers after, 1=disable triggers
	 * @return int    <0 if KO, Id of created object if OK
	 */
	public function create($data, $notrigger = 0)
	{
		$res = parent::create($data, $notrigger);

		// Add log
		if ($res > 0) {
			$log = new Logs();
			$log->add($this, 'CREATE_OBJECT');
		}

		return $res;
	}

	/**
	 * Update object into database
	 *
	 * @param  array   data array, e.: array('my_field_name' => 'my_field_value', 'second_field_name' => 'second_field_value')
	 * @param  int     $notrigger 0=launch triggers after, 1=disable triggers
	 * @return int     <0 if KO, >0 if OK
	 */
	public function update($data, $notrigger = 0)
	{
		$res = parent::update($data, $notrigger);

		// Add log
		if ($res > 0) {
			$log = new Logs();
			$log->add($this, 'UPDATE_OBJECT');
		}

		return $res;
	}

	/**
	 * Delete object in database
	 *
	 * @param  int  $notrigger 0=launch triggers after, 1=disable triggers
	 * @return int  <0 if KO, >0 if OK
	 */
	public function delete($notrigger = 0)
	{
		$res = parent::delete($notrigger);

		// Add log
		if ($res > 0) {
			$log = new Logs();
			$log->add($this, 'DELETE_OBJECT');
		}

		return $res;
	}

	/**
	 * Returns the reference to the following non used object depending on the active numbering model
	 * defined into MODULE_RIGHTS_CLASS_ADDON
	 *
	 * @param  string      $const_name_prefix     Constant name prefix
	 * @param  string      $model_name            Numbering model name
	 * @param  Societe     $soc                   Object thirdparty
	 * @return string      Reference
	 */
	public function getNextNumRef($const_name_prefix = '', $model_name = '', $soc = '')
	{
		global $conf, $langs, $dolibase_config;

		$const_name = (! empty($const_name_prefix) ? $const_name_prefix : get_rights_class(true)) . '_ADDON';

		if (! empty($conf->global->$const_name))
		{
			$file = $conf->global->$const_name;
			$classname = 'NumModel'.ucfirst($file);
			$exists = false;

			// Include file with class
			$dirmodels = array(
				dolibase_buildpath("core/num_models/"),
				dol_buildpath($dolibase_config['module']['folder']."/core/num_models/")
			);

			foreach ($dirmodels as $dir)
			{
				if (is_dir($dir))
				{
					// Load file with numbering class (if found)
					$exists|=@include_once $dir.$file.".php";
				}
			}

			if (! $exists)
			{
				dol_print_error('',"Failed to include file ".$file);
				return '';
			}

			$obj = new $classname($const_name_prefix, $model_name);
			$numref = "";
			$numref = $obj->getNextValue($soc);

			if ($numref != "")
			{
				return $numref;
			}
			else
			{
				$this->error = $obj->error;
				setEventMessage($this->error, 'errors');
				return "";
			}
		}
		else
		{
			$langs->load("errors");
			$this->error = $langs->trans("ErrorModuleSetupNotComplete");
			setEventMessage($this->error, 'errors');
			return "";
		}
	}

	/**
	 * Return clicable name (with picto eventually)
	 *
	 * @param      int        $withpicto     0=No picto, 1=Include picto into link, 2=Only picto
	 * @param      string     $title         Tooltip title
	 * @return     string                    Chain with URL
	 */
	public function getNomUrl($withpicto = 0, $title = '')
	{
		global $langs, $dolibase_config;

		$ref_field = $this->ref_field_name;

		$result = '';
		$label  = (! empty($title) ? '<u>' . $langs->trans($title) . '</u><br>' : '');
		if (! empty($this->$ref_field)) {
			$label .= '<b>' . $langs->trans('Ref') . ':</b> ' . $this->$ref_field;
		}
		// Add tooltip details
		foreach ($this->tooltip_details as $key => $value) {
			$label .= '<br><b>' . $langs->trans($key) . ':</b> ' . $value;
		}

		$url = dol_buildpath((! empty($this->card_url) ? $this->card_url : $dolibase_config['module']['folder'].'/card.php') . '?id='.$this->id, 1);
		$link = '<a href="'.$url.'" title="'.dol_escape_htmltag($label, 1).'" class="classfortooltip">';
		$linkend = '</a>';

		$picto = (! empty($this->picto) ? $this->picto : $dolibase_config['module']['picture'].'@'.$dolibase_config['module']['folder']);

		if ($withpicto) $result.= ($link.img_object($label, $picto, 'class="classfortooltip"').$linkend);
		if ($withpicto && $withpicto != 2) $result.= ' ';
		$result.= $link.$this->$ref_field.$linkend;

		return $result;
	}

	/**
	 * Return label of status of object (draft, validated, ...)
	 *
	 * @param      int        $mode     0=long label, 1=short label, 2=Picto + short label, 3=Picto, 4=Picto + long label, 5=Short label + Picto
	 * @return     string     Label
	 */
	public function getLibStatut($mode = 0)
	{
		return ''; // temporary fix to allow display banner without errors
	}

	/**
	 * Get object image(s)
	 *
	 * @param     $default_image     Default image to show if no image is available
	 */
	public function getImage($default_image)
	{
		global $conf, $dolibase_config;

		$out = '';
		$image_available = false;
		$dir = $conf->{$this->modulepart}->dir_output;

		$out.= '<div class="floatleft inline-block valignmiddle divphotoref">';

		if (method_exists($this, 'show_photos'))
		{
			$max = 5;
			$width = 80;
			$photos = $this->show_photos($this->modulepart, $dir ,'small', $max, 0, 0, 0, $width);

			if ($this->nbphoto > 0) {
				$out.= $photos;
				$image_available = true;
			}
		}

		if (! $image_available)
		{
			$out.= '<div class="photoref">'.img_picto('', $default_image.'@'.$dolibase_config['module']['folder']).'</div>';
		}

		$out.= '</div>';

		return $out;
	}

	/**
	 * Create a document onto disk according to template module.
	 *
	 * @param      string     $model     Force template to use ('' to not force)
	 * @return     int                   0 if KO, 1 if OK
	 */
	public function generateDocument($model)
	{
		global $conf, $user, $langs, $dolibase_config;

		// Get parameters
		$hidedetails = (GETPOST('hidedetails', 'int') ? GETPOST('hidedetails', 'int') : (! empty($conf->global->MAIN_GENERATE_DOCUMENTS_HIDE_DETAILS) ? 1 : 0));
		$hidedesc = (GETPOST('hidedesc', 'int') ? GETPOST('hidedesc', 'int') : (! empty($conf->global->MAIN_GENERATE_DOCUMENTS_HIDE_DESC) ? 1 : 0));
		$hideref = (GETPOST('hideref', 'int') ? GETPOST('hideref', 'int') : (! empty($conf->global->MAIN_GENERATE_DOCUMENTS_HIDE_REF) ? 1 : 0));

		// Save last template used to generate document
		if ($model) {
			$this->setDocModel($user, $model);
			$this->model_pdf = $model;
		}

		// Define output language
		$outputlangs = $langs;
		$newlang = '';
		if ($conf->global->MAIN_MULTILANGS && empty($newlang) && ! empty($_REQUEST['lang_id']))
			$newlang = $_REQUEST['lang_id'];
		if ($conf->global->MAIN_MULTILANGS && empty($newlang))
			$newlang = $this->thirdparty->default_lang;
		if (! empty($newlang)) {
			$outputlangs = new Translate("", $conf);
			$outputlangs->setDefaultLang($newlang);
		}

		// Model to use
		if (! dol_strlen($model))
		{
			$const_name = get_rights_class(true) . '_ADDON_PDF';

			if (! empty($conf->global->$const_name))
			{
				$model = $conf->global->$const_name;
			}
			else
			{
				$model = 'azur';
			}
		}

		// Get model path
		$modelpath = $dolibase_config['main']['path'] . '/core/doc_models/';
		$dirmodels = array(
			$dolibase_config['main']['path'] . '/core/doc_models/' => dolibase_buildpath("core/doc_models/"),
			$dolibase_config['module']['folder'] . '/core/doc_models/' => dol_buildpath($dolibase_config['module']['folder']."/core/doc_models/")
		);

		foreach ($dirmodels as $path => $dir)
		{
			foreach(array('doc', 'pdf') as $prefix)
			{
				if (file_exists($dir.$prefix."_".$model.".modules.php")) {
					$modelpath = $path;
					break 2;
				}
			}
		}

		// Generate document
		$result = $this->commonGenerateDocument($modelpath, $model, $outputlangs, $hidedetails, $hidedesc, $hideref);
		if ($result <= 0) {
			setEventMessages($this->error, $this->errors, 'errors');
		}

		return $result;
	}

	/**
	 * Delete document from disk.
	 *
	 * @return     int     0 if KO, 1 if OK
	 */
	public function deleteDocument()
	{
		global $conf, $langs;

		if ($this->id > 0)
		{
			require_once DOL_DOCUMENT_ROOT . '/core/lib/files.lib.php';

			$langs->load("other");
			$upload_dir = $conf->{$this->modulepart}->dir_output;
			$file = $upload_dir . '/' . GETPOST('file');
			$result = dol_delete_file($file, 0, 0, 0, $object);
			if ($result) {
				setEventMessages($langs->trans("FileWasRemoved", GETPOST('file')), null, 'mesgs');
			}
			else {
				setEventMessages($langs->trans("ErrorFailToDeleteFile", GETPOST('file')), null, 'errors');
			}

			return $result;
		}

		return 0;
	}
}
