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

global $dolibase_config;

/**
 * Dolibase main configuration
 */

$dolibase_config['main'] = array(
	'version'             => '2.9.5', // possible values: a.b.c-alpha, a.b.c-beta, a.b.c-rcX or a.b.c
	'link'                => 'https://github.com/AXeL-dev/dolibase',
	'path'                => get_dolibase_path(),
	'tables'              => array('logs'),
	'use_ajax_on_confirm' => true
);

/**
 * Dolibase langs configuration
 */

$dolibase_config['langs'] = array(
	'path' => preg_replace('/^\//', '', $dolibase_config['main']['path']) // the same as main path but without the first slash '/'
);
