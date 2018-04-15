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

/**
 * Widget class
 */

class Widget
{
	/**
	 * @var ModeleBoxes Dolibarr box object
	 */
	private $widget;

	/**
	 * Constructor
	 * 
	 * @param     $box        Dolibarr box object
	 * @param     $db         Database handler
	 * @param     $name       Widget name
	 * @param     $picture    Widget picture (picture file should have 'object_' prefix)
	 * @param     $position   Widget position
	 * @param     $enabled    Enable or disable widget
	 * @param     $param      More widget options
	 */
	public function __construct(&$box, $db, $name, $picture, $position = 1, $enabled = 1, $param = '')
	{
		global $dolibase_config, $langs;

		// Check if config array is empty
		if (empty($dolibase_config)) die('Dolibase::Widget::Error module configuration not found.');

		// Save box object for further use
		$this->widget = $box;

		// Load language files
		$langs->load('boxes');
		$langs->load($dolibase_config['lang_files'][0]);

		// Widget configuration
		$this->widget->db                = $db;
		$this->widget->boxcode           = "";
		$this->widget->boxlabel          = $langs->trans($name);
		$this->widget->boximg            = $picture."@".$dolibase_config['module_folder'];
		$this->widget->position          = $position;
		$this->widget->depends           = $dolibase_config['module_depends'];
		$this->widget->info_box_head     = array();
		$this->widget->info_box_contents = array();
		$this->widget->enabled           = $enabled;
		$this->widget->param             = $param;
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
		$this->widget->max = $max;

		// Set widget title
		$this->widget->info_box_head = array(
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

		$this->widget->info_box_head['sublink']  = $link;
		$this->widget->info_box_head['subpicto'] = $picture."@".$dolibase_config['module_folder'];
		$this->widget->info_box_head['subtext']  = $langs->trans($tooltip);
		$this->widget->info_box_head['target']   = $target;
		$this->widget->info_box_head['subclass'] = $class;
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
		$lines_count = count($this->widget->info_box_contents);

		$current_line = $lines_count > 0 ? $lines_count - 1 : 0;

		$cols_count = count($this->widget->info_box_contents[$current_line]);

		$this->widget->info_box_contents[$current_line][] = array(
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
			$this->widget->info_box_contents[$current_line][0]['tr'] = $first_col_attr;
		}
	}

	/**
	 * Add a new line to widget
	 *
	 */
	public function newLine()
	{
		$new_line = count($this->widget->info_box_contents);

		$this->widget->info_box_contents[$new_line] = array();
	}
}