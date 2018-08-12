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

dolibase_include_once('/core/class/crud_object.php');
dolibase_include_once('/core/class/logs.php');

/**
 * CustomObject class
 */

class CustomObject extends CrudObject
{
	/**
	 * @var string Id to identify managed object
	 */
	public $element = ''; // e.: 'my_object'
	/**
	 * @var string Name of table without prefix where object is stored
	 */
	public $table_element = ''; // e.: 'my_table'
	/**
	 * @var string Banner picture
	 */
	public $picto = ''; // e.: 'my_picture@my_module'
	/**
	 * @var array Fetch fields
	 */
	public $fetch_fields = array(); // e.: array('field_1', 'field_2', 'field_3')
	/**
	 * @var string Primary key name (id field)
	 */
	public $pk_name = 'rowid';
	/**
	 * @var string Ref. field name
	 */
	public $ref_field_name = 'ref';


	/**
	 * Constructor
	 * 
	 */
	public function __construct()
	{
		global $db;

		$this->db = $db;
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
		if (DOLIBASE_ENABLE_LOGS && $res > 0) {
			$log = new Logs();
			$log->add($this->id, 'CREATE_OBJECT');
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
		if (DOLIBASE_ENABLE_LOGS && $res > 0) {
			$log = new Logs();
			$log->add($this->id, 'UPDATE_OBJECT');
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
		if (DOLIBASE_ENABLE_LOGS && $res > 0) {
			$log = new Logs();
			$log->add($this->id, 'DELETE_OBJECT');
		}

		return $res;
	}

	/**
	 *  Returns the reference to the following non used object depending on the active numbering model
	 *  defined into MODULE_RIGHTS_CLASS_ADDON
	 *
	 *  @param	Societe		$soc  	Object thirdparty
	 *  @return string      		Reference
	 */
	public function getNextNumRef($soc = '')
	{
		global $conf, $langs, $dolibase_config;

		$const_name = get_rights_class(true) . '_ADDON';

		if (! empty($conf->global->$const_name))
		{
			$mybool=false;

			$file = $conf->global->$const_name;
			$classname = 'NumModel'.ucfirst($file);

			// Include file with class
			$dirmodels = array_merge(array('/'), (array) $conf->modules_parts['models']);
			foreach ($dirmodels as $reldir) {
				$dir = dol_buildpath($reldir."/dolibase/core/num_models/");
				$mod_dir = dol_buildpath($reldir."/".$dolibase_config['module']['folder']."/dolibase/core/num_models/");
				
				$dir = ! is_dir($dir) ? $mod_dir : $dir;

				// Load file with numbering class (if found)
				$mybool|=@include_once $dir.$file.".php";
			}

			if (! $mybool)
			{
				dol_print_error('',"Failed to include file ".$file);
				return '';
			}

			$obj = new $classname();
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
	 *	Return clicable name (with picto eventually)
	 *
	 *	@param		int		$withpicto		0=No picto, 1=Include picto into link, 2=Only picto
	 *	@param		string	$title			Tooltip title
	 *	@return		string					Chain with URL
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

		$link = '<a href="'.dol_buildpath('/'.$dolibase_config['module']['folder'].'/card.php?id='.$this->id, 1).'" title="'.dol_escape_htmltag($label, 1).'" class="classfortooltip">';
		$linkend = '</a>';

		$picto = $dolibase_config['module']['picture'].'@'.$dolibase_config['module']['folder'];

		if ($withpicto) $result.= ($link.img_object($label, $picto, 'class="classfortooltip"').$linkend);
		if ($withpicto && $withpicto != 2) $result.= ' ';
		$result.= $link.$this->$ref_field.$linkend;

		return $result;
	}

	/**
	 *  Create a document onto disk according to template module.
	 *
	 *  @param	    string		$model			Force template to use ('' to not force)
	 *  @return     int         				0 if KO, 1 if OK
	 */
	public function generateDocument($model)
	{
		global $conf, $user, $langs;

		// Get parameters
		$hidedetails = (GETPOST('hidedetails', 'int') ? GETPOST('hidedetails', 'int') : (! empty($conf->global->MAIN_GENERATE_DOCUMENTS_HIDE_DETAILS) ? 1 : 0));
		$hidedesc = (GETPOST('hidedesc', 'int') ? GETPOST('hidedesc', 'int') : (! empty($conf->global->MAIN_GENERATE_DOCUMENTS_HIDE_DESC) ? 1 : 0));
		$hideref = (GETPOST('hideref', 'int') ? GETPOST('hideref', 'int') : (! empty($conf->global->MAIN_GENERATE_DOCUMENTS_HIDE_REF) ? 1 : 0));

		// Save last template used to generate document
		if ($model) $this->setDocModel($user, $model);

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

		$modelpath = DOLIBASE_PATH.'/core/doc_models/';

		$result = $this->commonGenerateDocument($modelpath, $model, $outputlangs, $hidedetails, $hidedesc, $hideref);
		if ($result <= 0) {
			setEventMessages($this->error, $this->errors, 'errors');
		}

		return $result;
	}

	/**
	 *  Delete document from disk.
	 *
	 *  @return     int         				0 if KO, 1 if OK
	 */
	public function deleteDocument()
	{
		global $conf, $langs;

		if ($this->id > 0)
		{
			require_once DOL_DOCUMENT_ROOT . '/core/lib/files.lib.php';

			$langs->load("other");
			$rights_class = get_rights_class();
			$upload_dir = $conf->$rights_class->dir_output;
			$file = $upload_dir . '/' . GETPOST('file');
			$result = dol_delete_file($file, 0, 0, 0, $object);
			if ($result)
				setEventMessages($langs->trans("FileWasRemoved", GETPOST('file')), null, 'mesgs');
			else
				setEventMessages($langs->trans("ErrorFailToDeleteFile", GETPOST('file')), null, 'errors');

			return $result;
		}

		return 0;
	}
}