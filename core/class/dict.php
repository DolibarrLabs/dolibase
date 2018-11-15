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

dolibase_include_once('/core/class/crud_object.php');

/**
 * Dictionary class
 */

class Dictionary
{
	/**
	 * Returns dictionary active lines list
	 *
	 * @param     $dict_table     dictionary table name (without prefix)
	 * @param     $key_field      list key field, e.: 'rowid'
	 * @param     $value_field    list value field, e.: 'label'
	 * @param     $sort_field     list sort field, e.: 'label'
	 * @param     $sort_order     list sort order, e.: 'DESC' or 'ASC'
	 * @return    array           dictionary active lines list
	 */
	public static function get_active($dict_table, $key_field = 'rowid', $value_field = 'label', $sort_field = 'label', $sort_order = 'ASC')
	{
		return self::get_list($dict_table, $key_field, $value_field, true, $sort_field, $sort_order);
	}

	/**
	 * Returns dictionary all lines list
	 *
	 * @param     $dict_table     dictionary table name (without prefix)
	 * @param     $key_field      list key field, e.: 'rowid'
	 * @param     $value_field    list value field, e.: 'label'
	 * @param     $sort_field     list sort field, e.: 'label'
	 * @param     $sort_order     list sort order, e.: 'DESC' or 'ASC'
	 * @return    array           dictionary all lines list
	 */
	public static function get_all($dict_table, $key_field = 'rowid', $value_field = 'label', $sort_field = 'label', $sort_order = 'ASC')
	{
		return self::get_list($dict_table, $key_field, $value_field, false, $sort_field, $sort_order);
	}

	/**
	 * Returns dictionary lines list
	 *
	 * @param     $dict_table     dictionary table name (without prefix)
	 * @param     $key_field      list key field, e.: 'rowid'
	 * @param     $value_field    list value field, e.: 'label'
	 * @param     $only_active    get only active lines or not
	 * @param     $sort_field     list sort field, e.: 'label'
	 * @param     $sort_order     list sort order, e.: 'DESC' or 'ASC'
	 * @return    array           dictionary lines list
	 */
	private static function get_list($dict_table, $key_field = 'rowid', $value_field = 'label', $only_active = false, $sort_field = 'label', $sort_order = 'ASC')
	{
		global $langs;

		$list = array();

		$dict = new CrudObject();
		$dict->setTableName($dict_table);
		$dict->fetch_fields = array($key_field, $value_field);
		$where = $only_active ? 'active = 1' : '';

		$result = $dict->fetchAll(0, 0, $sort_field, $sort_order, '', '', $where);

		if ($result) {
			foreach ($dict->lines as $line) {
				$list[$line->$key_field] = $langs->trans($line->$value_field);
			}
		}

		return $list;
	}
}
