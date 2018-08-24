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
 * Version
 *
 * Possible values: a.b.c-alpha, a.b.c-beta, a.b.c-rcX or a.b.c
 */
if (! defined('DOLIBASE_VERSION')) define('DOLIBASE_VERSION', '2.0.0-beta');

/**
 * Environment
 *
 * Possible values: 'dev', 'prod'
 */
if (! defined('DOLIBASE_ENV')) define('DOLIBASE_ENV', 'dev');

/**
 * Link
 */
if (! defined('DOLIBASE_LINK')) define('DOLIBASE_LINK', 'https://github.com/AXeL-dev/dolibase');

/**
 * Path (short path)
 *
 * const.php file should be in dolibase root folder otherwise path value may be wrong
 *
 * Possible values: '/dolibase', '/module_folder/dolibase', '/custom/module_folder/dolibase'
 */
if (! defined('DOLIBASE_PATH')) define('DOLIBASE_PATH', str_replace(DOL_DOCUMENT_ROOT, '', __DIR__));

/**
 * Root
 *
 * The same as Dolibase Path except that '/custom' string should never be included
 *
 * Possible values: '/dolibase', '/module_folder/dolibase'
 */
if (! defined('DOLIBASE_ROOT')) define('DOLIBASE_ROOT', str_replace('/custom', '', DOLIBASE_PATH));

/**
 * Language files root
 *
 * The same as Dolibase Root but without the first slash '/'
 */
if (! defined('DOLIBASE_LANGS_ROOT')) define('DOLIBASE_LANGS_ROOT', substr(DOLIBASE_ROOT, 1));

/**
 * Allow functions chaining
 *
 * Require PHP >= 5
 */
if (! defined('DOLIBASE_ALLOW_FUNC_CHAINING')) define('DOLIBASE_ALLOW_FUNC_CHAINING', true);

/**
 * Use AJAX on confirmation
 *
 * Values should be 1/0 instead of true/false because the function where this is used takes an integer value instead of boolean as parameter
 */
if (! defined('DOLIBASE_USE_AJAX_ON_CONFIRM')) define('DOLIBASE_USE_AJAX_ON_CONFIRM', 1);

/**
 * Array of tables that should be loaded by Dolibase
 *
 * Tables files should be in /dolibase/sql folder
 */
if (! defined('DOLIBASE_LOAD_TABLES')) define('DOLIBASE_LOAD_TABLES', array('logs'));

/**
 * Enable Dolibase logs
 *
 * Logs are currently saved only when creating/modifying/deleting an object
 */
if (! defined('DOLIBASE_ENABLE_LOGS')) define('DOLIBASE_ENABLE_LOGS', (in_array('logs', DOLIBASE_LOAD_TABLES) ? true : false));

/**
 * Define if Dolibase should check for module updates or not
 */
if (! defined('DOLIBASE_CHECK_FOR_UPDATE')) define('DOLIBASE_CHECK_FOR_UPDATE', true);
