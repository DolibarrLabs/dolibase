<?php

// Load Dolibase NumModel class
dolibase_include_once('/core/class/num_model.php');

/**
 * ${model_classname} class
 *
 * Class to manage module numbering rules ${model_name}
 */

class ${model_classname} extends NumModel
{
	public $nom;
	public $version;
	public $description;
	protected $const_name;
	protected $table_name;
	protected $field_name;

	/**
	 * Constructor
	 *
	 * @param     $const_name_prefix     Constant name prefix
	 * @param     $model_name            Numbering model name
	 */
	public function __construct($const_name_prefix, $model_name = '')
	{
		$this->nom = '${model_name}';
		$this->version = '${model_version}'; // 'development', 'experimental', 'dolibarr'
		$this->description = '${model_description}';
		$this->const_name = '${const_name}';
		$this->table_name = '${table_name}';
		$this->field_name = '${field_name}';
	}

	/**
	 * Return description of numbering model
	 *
	 * @return     string      Text with description
	 */
	public function info()
	{
		global $db, $conf, $langs, $dolibase_config;

		$langs->load($dolibase_config['other']['lang_files'][0]);

		$module_name = $langs->transnoentities($dolibase_config['module']['name']);
		$form = new Form($db);

		$text = $langs->trans($this->description)."<br>\n";
		$text.= '<form action="'.$_SERVER["PHP_SELF"].'" method="POST">';
		$text.= '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
		$text.= '<input type="hidden" name="action" value="updateMask">';
		$text.= '<input type="hidden" name="maskconst" value="'.$this->const_name.'">';
		$text.= '<table class="nobordernopadding" width="100%">';

		$tooltip = $langs->trans("GenericMaskCodes", $module_name, $module_name);
		$tooltip.= $langs->trans("GenericMaskCodes2");
		$tooltip.= $langs->trans("GenericMaskCodes3");
		$tooltip.= $langs->trans("GenericMaskCodes4a", $module_name, $module_name);
		$tooltip.= $langs->trans("GenericMaskCodes5");

		$text.= '<tr><td>'.$langs->trans("Mask").':</td>';
		$text.= '<td align="right">'.$form->textwithpicto('<input type="text" class="flat" size="24" name="mask" value="'.$conf->global->{$this->const_name}.'">', $tooltip, 1, 1).'</td>';

		$text.= '<td align="left" rowspan="2">&nbsp; <input type="submit" class="button" value="'.$langs->trans("Modify").'" name="Button"></td>';

		$text.= '</tr>';

		$text.= '</table>';
		$text.= '</form>';

		return $text;
	}

	/**
	 * Return an example of numbering
	 *
	 * @return string      Example
	 */
	public function getExample()
	{
		global $langs;

		$example = $this->getNextValue();

		if (! $example)
		{
			$example = $langs->trans('NotConfigured');
		}
		return $example;
	}

	/**
	 * Return next free value
	 *
	 * @param  Societe      $objsoc     Object thirdparty
	 * @return string                   Value if KO, <0 if KO
	 */
	public function getNextValue($objsoc = null)
	{
		global $db, $conf, $langs;

		require_once DOL_DOCUMENT_ROOT . '/core/lib/functions2.lib.php';

		// We get cursor rule
		$mask = $conf->global->{$this->const_name};

		if (! $mask)
		{
			$this->error = $langs->trans('ErrorModuleSetupNotComplete');
			return 0;
		}

		$date = time();
		$next_value = get_next_value($db, $mask, $this->table_name, $this->field_name, '', $objsoc, $date, 'next', false);

		return $next_value;
	}
}
