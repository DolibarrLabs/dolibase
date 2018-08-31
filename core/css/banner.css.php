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

header('Content-Type: text/css');

?>

/*--- Hide Banner second picture ---*/

.arearef .floatleft .divphotoref:nth-child(2) {
	display: none !important;
}

/*--- Set default image size ---*/

.divphotoref .photoref img {
	width: 40px;
	height: 40px;
}

<?php
