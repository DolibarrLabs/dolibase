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

global $langs, $conf, $dolibase_config, $db;

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
	<div class="tabsAction force-center">
		<a href="<?php echo dol_buildpath($dolibase_config['module']['folder'].'/admin/'.$dolibase_config['other']['about_page'].'?mainmenu=home&action=report_bug', 1); ?>" class="buttonDelete"><?php echo $langs->trans('ReportBug'); ?></a>
	</div>
</div>

<?php

$action = GETPOST('action', 'alpha');

if ($action == 'report_bug')
{

$log_file_name = basename($conf->global->SYSLOG_FILE);
$log_file_link = '<a href="'.DOL_URL_ROOT.'/document.php?modulepart=logs&file='.$log_file_name.'" title="'.$langs->transnoentities('Download').'">'.$log_file_name.'</a>';
echo $langs->trans('ReportBugNotice', $log_file_link);

if (empty($conf->syslog->enabled)) {
	$enable_log_link = '<a href="'.dol_buildpath($dolibase_config['module']['folder'].'/admin/'.$dolibase_config['other']['about_page'].'?mainmenu=home&action=enable_log', 1).'">'.img_picto($langs->trans('ClickToEnable'), 'switch_off').'</a>';
	echo img_warning().'&nbsp;'.$langs->trans('EnableLogModule', $enable_log_link);
}

?>

<br><br>

<table class="noborder allwidth">
	<tr class="liste_titre">
		<td width="20%"><?php echo $langs->trans('TechnicalInformations'); ?></td>
		<td><?php echo $langs->trans('Value'); ?></td>
	</tr>
	<tr>
		<td><?php echo $langs->trans('DolibarrVersion'); ?></td>
		<td><?php echo DOL_VERSION; ?></td>
	</tr>
	<tr>
		<td><?php echo $langs->trans('ModuleVersion'); ?></td>
		<td><?php echo $dolibase_config['module']['version']; ?></td>
	</tr>
	<tr>
		<td><?php echo $langs->trans('PHPVersion'); ?></td>
		<td><?php echo phpversion(); ?></td>
	</tr>
	<tr>
		<td><?php echo $langs->trans('DatabaseVersion'); ?></td>
		<td><?php echo $db->getVersion(); ?></td>
	</tr>
	<tr>
		<td><?php echo $langs->trans('WebServerVersion'); ?></td>
		<td><?php echo $_SERVER['SERVER_SOFTWARE']; ?></td>
	</tr>
</table>

<?php

}
