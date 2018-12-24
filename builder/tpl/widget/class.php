<?php

// Load Dolibase
dol_include_once('${module_folder}/autoload.php');

// Load Dolibase Widget class
${dolibase_class_include}

/**
 * Class to manage the widget box
 *
 * Warning: for the box to be detected correctly by dolibarr,
 * the filename should be the lowercase classname
 */
class ${widget_class_name} extends ${dolibase_class_name}
{
	/**
	 * @var Widget Label
	 */
	public $boxlabel = '${widget_name}';
	/**
	 * @var Widget Picture
	 */
	public $boximg = '${widget_picture}';
	/**
	 * @var Widget Position
	 */
	public $position = ${widget_position};
	/**
	 * @var Widget is Enabled
	 */
	public $enabled = ${enable_widget};


	/**
	 * Load data into info_box_contents array to show array later. Called by Dolibarr before displaying the box.
	 *
	 * @param int $max Maximum number of records to load
	 * @return void
	 */
	public function loadBox($max = 5)
	{
		$this->setTitle('${widget_title}');

		$this->addContent('Add some content here..');
	}
}
