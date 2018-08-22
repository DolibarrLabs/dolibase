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

global $dolibase_config;

if (! defined('DOLIBASE_VERSION')) define('DOLIBASE_VERSION', '1.8.0'); // a.b.c-alpha, a.b.c-beta, a.b.c-rcX or a.b.c
if (! defined('DOLIBASE_LINK')) define('DOLIBASE_LINK', 'https://github.com/AXeL-dev/dolibase');
if (! defined('DOLIBASE_PATH')) define('DOLIBASE_PATH', (is_dir(DOL_DOCUMENT_ROOT.'/dolibase') ? '/dolibase' : '/'.$dolibase_config['module']['folder'].'/dolibase'));
if (! defined('DOLIBASE_LANGS_ROOT')) define('DOLIBASE_LANGS_ROOT', (DOLIBASE_PATH == '/dolibase' ? 'dolibase' : $dolibase_config['module']['folder'].'/dolibase'));
if (! defined('DOLIBASE_ALLOW_FUNC_CHAINING')) define('DOLIBASE_ALLOW_FUNC_CHAINING', true); // functions chaining may not work on PHP 4
if (! defined('DOLIBASE_DEBUG_MODE')) define('DOLIBASE_DEBUG_MODE', false); // turn it to true to get more informations about errors
if (! defined('DOLIBASE_USE_AJAX_ON_CONFIRM')) define('DOLIBASE_USE_AJAX_ON_CONFIRM', 1); // i use '1' here instead of 'true' because the function where this is used takes int value instead of boolean as parameter
if (! defined('DOLIBASE_ENABLE_LOGS')) define('DOLIBASE_ENABLE_LOGS', true);
if (! defined('DOLIBASE_CHECK_FOR_UPDATE')) define('DOLIBASE_CHECK_FOR_UPDATE', true);
