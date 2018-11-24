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

global $langs, $dolibase_config;

$langs->load("page@".$dolibase_config['langs']['path']);

?>

<div class="center">
	<img src="<?php echo dolibase_buildurl('/core/img/under-construction.png'); ?>" alt="under construction">
	<h1>
		<strong>
			<i><?php echo $langs->trans('PageUnderConstruction'); ?></i>
		</strong>
	</h1>
</div>

<?php
