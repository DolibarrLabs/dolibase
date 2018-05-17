<?php

// Load Dolibase config file for this module (mandatory)
dol_include_once('/nativemodule/config.php');
// Load CustomWidget class
dol_include_once('/nativemodule/core/boxes/custom_widget.php');

/**
 * Class to manage the box
 *
 * Warning: for the box to be detected correctly by dolibarr,
 * the filename should be the lowercase classname
 */
class NativeBox extends CustomWidget
{
	/**
	 * @var Widget Label
	 */
	public $boxlabel = "NativeWidget";
	/**
	 * @var Widget Picture
	 */
	public $boximg = "mywidget.png";


	/**
	 * Load data into info_box_contents array to show array later. Called by Dolibarr before displaying the box.
	 *
	 * @param int $max Maximum number of records to load
	 * @return void
	 */
	public function loadBox($max = 5)
	{
		$this->setTitle("NativeWidgetTitle");

		$this->setLink("http://example.com", "link.png");

		$this->addContent("My column 1");

		$this->addContent("My column 2");

		$this->addContent("My column 3");

		$this->newLine();

		$this->addContent("My column 4", 'align="center" colspan="3"');
	}
}
