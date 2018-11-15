<?php

// Load Dolibase CustomObject class
dolibase_include_once('/core/class/custom_object.php');

class ${object_classname} extends CustomObject
{
	/**
	 * @var string Id to identify managed object
	 */
	public $element = '${object_element}';
	/**
	 * @var string Name of table without prefix where object is stored
	 */
	public $table_element = '${object_table}';
	/**
	 * @var string Primary key name (id field)
	 */
	public $pk_name = '${pk_name}';

	/**
	 * Constructor
	 * 
	 */
	public function __construct()
	{
		$this->fetch_fields = array(${fetch_fields});
		$this->date_fields = array(${date_fields});

		parent::__construct();
	}

	/**
	 * Return clicable name (with picto eventually)
	 *
	 * @param      int        $withpicto     0=No picto, 1=Include picto into link, 2=Only picto
	 * @param      string     $title         Tooltip title
	 * @return     string                    Chain with URL
	 */
	public function getNomUrl($withpicto = 0, $title = '${tooltip_title}')
	{
		return parent::getNomUrl($withpicto, $title);
	}
}
