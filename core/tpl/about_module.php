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

if (empty($picture)) {
	$picture = 'object_'.$dolibase_config['module']['picture'];
}

?>

<br>
<div class="modPicture">
	<img src="<?php echo dol_buildpath($dolibase_config['module']['folder'].'/img/'.$picture, 1); ?>" />
</div>
<div>
	<div>
		<a href="<?php echo $dolibase_config['module']['url']; ?>" target="_blank">
			<b><?php echo $langs->trans($dolibase_config['module']['name']); ?></b>
		</a>
		<span><?php echo ' : '.$langs->trans($dolibase_config['module']['desc']); ?></span>
	</div>
	<br>
	<div>
		<span><?php echo $langs->trans('DevelopedBy'); ?></span>
		<a href="<?php echo $dolibase_config['author']['url']; ?>" target="_blank"><?php echo $dolibase_config['author']['name']; ?></a>
		<span><?php echo '. '.$langs->trans('DolibaseVersion'); ?></span>
		<a href="<?php echo $dolibase_config['main']['link']; ?>" target="_blank"><?php echo $dolibase_config['main']['version']; ?></a>
	</div>
	<br>
	<div>
		<span><?php echo $langs->trans('ForAnyQuestions'); ?></span>
		<a href="mailto:<?php echo $dolibase_config['author']['email']; ?>"><?php echo $dolibase_config['author']['email']; ?></a>
	</div>
	<br>
	<div>
		<span><?php echo $langs->trans('FindMyModules'); ?></span>
		<a href="<?php echo $dolibase_config['author']['dolistore_url']; ?>" target="_blank"><?php echo $langs->trans('Dolistore'); ?></a>
	</div>
</div>
<br>

<?php
