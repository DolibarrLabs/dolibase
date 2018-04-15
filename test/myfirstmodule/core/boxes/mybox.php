<?php
/* <one line to give the program's name and a brief idea of what it does.>
 * Copyright (C) <year>  <name of author>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * \file    core/boxes/mybox.php
 * \ingroup mymodule
 * \brief   Example box definition.
 *
 * Put detailed description here.
 */

/** Includes */
include_once DOL_DOCUMENT_ROOT . "/core/boxes/modules_boxes.php";

// Load Dolibase config file for this module (mandatory)
dol_include_once('/myfirstmodule/config.php');
// Load Dolibase Widget class
dolibase_include_once('/core/class/widget.php');

/**
 * Class to manage the box
 *
 * Warning: for the box to be detected correctly by dolibarr,
 * the filename should be the lowercase classname
 */
class MyBox extends ModeleBoxes
{
	/**
	 * @var Dolibase Widget handler
	 */
	private $widget;

	/**
	 * Constructor
	 *
	 * @param DoliDB $db Database handler
	 * @param string $param More parameters
	 */
	public function __construct($db, $param = '')
	{
		// Create Widget using Dolibase
		$this->widget = new Widget($this, $db, "MyWidget", "mywidget.png");
	}

	/**
	 * Load data into info_box_contents array to show array later. Called by Dolibarr before displaying the box.
	 *
	 * @param int $max Maximum number of records to load
	 * @return void
	 */
	public function loadBox($max = 5)
	{
		$this->widget->setTitle("MyWidgetTitle");

		$this->widget->setLink("http://example.com", "link.png");

		$this->widget->addContent("My column 1");

		$this->widget->addContent("My column 2");

		$this->widget->addContent("My column 3");

		$this->widget->newLine();

		$this->widget->addContent("My column 4", 'align="center" colspan="3"');
	}

	/**
	 * Method to show box. Called by Dolibarr eatch time it wants to display the box.
	 *
	 * @param array $head Array with properties of box title
	 * @param array $contents Array with properties of box lines
	 * @return void
	 */
	public function showBox($head = null, $contents = null, $nooutput = 0)
	{
		// You may make your own code here…
		// … or use the parent's class function using the provided head and contents templates
		parent::showBox($this->info_box_head, $this->info_box_contents);
	}
}
