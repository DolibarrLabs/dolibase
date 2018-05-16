<?php

// Load Dolibase config file for this module (mandatory)
include_once dirname(__FILE__) . '/../../config.php'; // we use dirname(__FILE__) because this file is included by Dolibarr admin/boxes.php file
//dol_include_once('/myfirstmodule/config.php'); // may work also
include_once DOL_DOCUMENT_ROOT . "/core/boxes/modules_boxes.php";

/**
 * Class to manage the box
 *
 * Warning: for the box to be detected correctly by dolibarr,
 * the filename should be the lowercase classname
 */
class NativeBox extends ModeleBoxes
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
	 * @var Widget Position
	 */
	public $position = 1;
	/**
	 * @var Widget is Enabled
	 */
	public $enabled = 1;

	/**
	 * Constructor
	 * 
	 * @param     $db         Database handler
	 * @param     $param      More widget options
	 */
	public function __construct($db, $param = '')
	{
		global $dolibase_config, $langs;

		// Check if config array is empty
		if (empty($dolibase_config)) die('Dolibase::Widget::Error module configuration not found.');

		// Load language files
		$langs->load('boxes');
		$langs->load($dolibase_config['lang_files'][0]);

		// Widget configuration
		$this->boxcode           = "";
		$this->boxlabel          = $langs->trans($this->boxlabel);
		$this->boximg            = $this->boximg."@".$dolibase_config['module_folder'];
		$this->depends           = $dolibase_config['module_depends'];
		$this->info_box_head     = array();
		$this->info_box_contents = array();

		parent::__construct($db, $param);
	}

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

	/**
	 * Set widget title
	 *
	 * @param     $title     widget title
	 * @param     $max       maximum number of rows to show (will be added to translated title if so)
	 */
	public function setTitle($title, $max = 5)
	{
		global $langs;

		// Use configuration value for max lines count
		$this->max = $max;

		// Set widget title
		$this->info_box_head = array(
			// Title text
			'text' => $langs->trans($title, $max),
			// Add a link
			'sublink' => '',
			// Sublink icon placed after the text
			'subpicto' => '',
			// Sublink icon HTML alt text
			'subtext' => '',
			// Sublink HTML target
			'target' => '',
			// HTML class attached to the picto and link
			'subclass' => 'center',
			// Limit and truncate with "…" the displayed text lenght, 0 = disabled
			'limit' => 0,
			// Adds translated " (Graph)" to a hidden form value's input (?)
			'graph' => false
		);
	}

	/**
	 * Set widget link
	 *
	 * @param     $link      widget link
	 * @param     $picture   link picture
	 * @param     $tooltip   tooltip text
	 * @param     $target    link target, use '' or '_blank' to open in a new window / tab
	 * @param     $class     link css class
	 */
	public function setLink($link, $picture, $tooltip = '', $target = '_self', $class = 'center" style="margin-right: 5px;')
	{
		global $dolibase_config, $langs;

		$this->info_box_head['sublink']  = $link;
		$this->info_box_head['subpicto'] = $picture."@".$dolibase_config['module_folder'];
		$this->info_box_head['subtext']  = $langs->trans($tooltip);
		$this->info_box_head['target']   = $target;
		$this->info_box_head['subclass'] = $class;
	}

	/**
	 * Add content to widget
	 *
	 * @param     $text                text to show
	 * @param     $attr                element attributes (align, colspan, ...)
	 * @param     $clean_text          allow HTML cleaning & truncation
	 * @param     $max_length          maximum text length (0 = disabled)
	 * @param     $first_col_attr      first column attributes
	 */
	public function addContent($text, $attr = 'align="center"', $clean_text = false, $max_length = 0, $first_col_attr = '')
	{
		$lines_count = count($this->info_box_contents);

		$current_line = $lines_count > 0 ? $lines_count - 1 : 0;

		$cols_count = count($this->info_box_contents[$current_line]);

		$this->info_box_contents[$current_line][] = array(
					// HTML properties of the TD element
					'td'           => $attr,
					// Fist line logo
					//'logo'         => 'mypicture@mymodule',
					// Main text
					'text'         => $text,
					// Secondary text
					//'text2'        => '<p><strong>Another text</strong></p>',
					// Unformatted text, usefull to load javascript elements
					//'textnoformat' => '',
					// Link on 'text' and 'logo' elements
					//'url'          => 'http://example.com',
					// Link's target HTML property
					//'target'       => '_blank',
					// Truncates 'text' element to the specified character length, 0 = disabled
					'maxlength'    => $max_length,
					// Prevents HTML cleaning (and truncation)
					'asis'         => ! $clean_text, // abbr.: asis = as it is
					// Same for 'text2'
					//'asis2'        => true
				);

		if ($cols_count == 0 && ! empty($first_col_attr)) {
			//  HTML properties of the TR element. Only available on the first column.
			$this->info_box_contents[$current_line][0]['tr'] = $first_col_attr;
		}
	}

	/**
	 * Add a new line to widget
	 *
	 */
	public function newLine()
	{
		$new_line = count($this->info_box_contents);

		$this->info_box_contents[$new_line] = array();
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
