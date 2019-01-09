<?php

// Load Dolibase
dol_include_once('/myfirstmodule/autoload.php');

// Load Dolibase Widget class
dolibase_include_once('/core/class/widget.php');

/**
 * Class to manage the box
 *
 * Warning: for the box to be detected correctly by dolibarr,
 * the filename should be the lowercase classname
 */
class MyBox extends Widget
{
	/**
	 * @var Widget Label
	 */
	public $boxlabel = "MyWidget";
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
		$this->setTitle("MyWidgetTitle");

		$this->setLink("http://example.com", "link.png");

		$this->addContent("My column 1");

		$this->addContent("My column 2");

		$this->addContent("My column 3");

		$this->newLine();

		$this->addContent("My column 4", 'align="center" colspan="3"');
	}
}
