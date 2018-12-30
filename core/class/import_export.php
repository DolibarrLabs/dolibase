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

/**
 * ImportExport class
 */

class ImportExport
{
	/**
	 * Add import/export js files to the page
	 *
	 * @param     $page     page handler/object
	 */
	public static function addJsFiles(&$page)
	{
		$page->appendToHead('<script type="text/javascript" src="'.dolibase_buildurl('vendor/FileSaver/FileSaver.min.js').'"></script>'."\n");

		$page->appendToHead('<script type="text/javascript" src="'.dolibase_buildurl('core/js/import.js.php').'"></script>'."\n");

		$page->appendToHead('<script type="text/javascript" src="'.dolibase_buildurl('core/js/export.js.php').'"></script>'."\n");
	}

	/**
	 * Add/print export button
	 *
	 * @param     $text      button text
	 * @param     $id        button id
	 * @param     $class     button class
	 * @param     $alt       button alt/title
	 */
	public static function addExportButton($text, $id = 'export', $class = 'butAction', $alt = '')
	{
		global $langs;

		echo '<a href="#" id="'.$id.'" class="'.$class.'"'.(empty($alt) ? '' : ' title="'.$alt.'"').'>'.$langs->trans($text).'</a>';
	}

	/**
	 * Add/print import button
	 *
	 * @param     $text              button text
	 * @param     $accept            files to accept, e.: '.txt|.png'
	 * @param     $id                button id
	 * @param     $file_input_id     hidden file input id
	 * @param     $class             button class
	 * @param     $alt               button alt/title
	 */
	public static function addImportButton($text, $accept, $id = 'import', $file_input_id = 'import-file-input', $class = 'butAction', $alt = '')
	{
		global $langs;

		echo '<a href="#" id="'.$id.'" class="'.$class.'"'.(empty($alt) ? '' : ' title="'.$alt.'"').'>'.$langs->trans($text).'</a>';
		echo '<input type="file" class="hidden" id="'.$file_input_id.'" accept="'.$accept.'"/>';
	}
}
