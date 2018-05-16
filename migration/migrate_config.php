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
 * Migrate dolibase new config to the old one
 * 
 * why not the opposite? well, for 2 reasons:
 *		1 - to keep compatibility with old versions
 *		2 - to avoid code rewriting without any benefit
 */

global $dolibase_config;

//var_dump($dolibase_config);

if (isset($dolibase_config['module']) && is_array($dolibase_config['module']))
{
	$dolibase_config['module_name']     = $dolibase_config['module']['name'];
	$dolibase_config['module_desc']     = $dolibase_config['module']['desc'];
	$dolibase_config['module_version']  = $dolibase_config['module']['version'];
	$dolibase_config['module_number']   = $dolibase_config['module']['number'];
	$dolibase_config['module_family']   = $dolibase_config['module']['family'];
	$dolibase_config['module_position'] = $dolibase_config['module']['position'];
	$dolibase_config['rights_class']    = $dolibase_config['module']['rights_class'];
	$dolibase_config['module_url']      = $dolibase_config['module']['url'];
	$dolibase_config['module_folder']   = $dolibase_config['module']['folder'];
	$dolibase_config['module_picture']  = $dolibase_config['module']['picture'];
	$dolibase_config['module_dirs']     = $dolibase_config['module']['dirs'];
	$dolibase_config['need_dolibarr']   = $dolibase_config['module']['dolibarr_min'];
	$dolibase_config['need_php']        = $dolibase_config['module']['php_min'];
	$dolibase_config['module_depends']  = $dolibase_config['module']['depends'];
	$dolibase_config['required_by']     = $dolibase_config['module']['required_by'];
	$dolibase_config['conflict_with']   = $dolibase_config['module']['conflict_with'];

	unset($dolibase_config['module']);
}

if (isset($dolibase_config['author']) && is_array($dolibase_config['author']))
{
	$dolibase_config['editor_name']   = $dolibase_config['author']['name'];
	$dolibase_config['editor_url']    = $dolibase_config['author']['url'];
	$dolibase_config['editor_email']  = $dolibase_config['author']['email'];
	$dolibase_config['dolistore_url'] = $dolibase_config['author']['dolistore_url'];

	unset($dolibase_config['author']);
}

if (isset($dolibase_config['other']) && is_array($dolibase_config['other']))
{
	$dolibase_config['setup_page_url'] = $dolibase_config['other']['setup_page'];
	$dolibase_config['about_page_url'] = $dolibase_config['other']['about_page'];
	$dolibase_config['lang_files']     = $dolibase_config['other']['lang_files'];
	$dolibase_config['menu_lang_file'] = $dolibase_config['other']['menu_lang_file'];
	$dolibase_config['top_menu_name']  = $dolibase_config['other']['top_menu_name'];

	unset($dolibase_config['other']);
}

if (isset($dolibase_config['numbering_model']) && is_array($dolibase_config['numbering_model']))
{
	$dolibase_config['num_model_table']  = $dolibase_config['numbering_model']['table'];
	$dolibase_config['num_model_field']  = $dolibase_config['numbering_model']['field'];
	$dolibase_config['num_model_prefix'] = $dolibase_config['numbering_model']['prefix'];

	unset($dolibase_config['numbering_model']);
}
