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

dolibase_include_once('/core/class/page.php');
dolibase_include_once('/core/class/logs.php');
include_once DOL_DOCUMENT_ROOT.'/core/lib/date.lib.php';

/**
 * LogPage class
 */

class LogPage extends Page
{
	/**
	 * Constructor
	 * 
	 * @param     $page_title     HTML page title
	 * @param     $access_perm    Access permission
	 */
	public function __construct($page_title, $access_perm = '')
	{
		global $langs, $dolibase_config;

		// Load lang files
		$langs->load("log_page@".$dolibase_config['main']['path']);

		parent::__construct($page_title, $access_perm);
	}

	/**
	 * Show banner
	 *
	 * @param     $object         object
	 * @param     $list_link      link to list
	 * @param     $morehtmlleft   more html in the left
	 */
	public function showBanner($object, $list_link = '', $morehtmlleft = '')
	{
		global $langs;

		$morehtml = (empty($list_link) ? '' : '<a href="'.dol_buildpath($list_link, 1).'">'.$langs->trans("BackToList").'</a>');

		dol_banner_tab($object, 'ref', $morehtml, 1, 'ref', 'ref', '', '', 0, $morehtmlleft);

		echo '<div class="underbanner clearboth"></div>';
	}

	/**
	 * Print logs
	 *
	 * @param     $object_id     object id
	 */
	public function printLogs($object_id)
	{
		global $langs, $dolibase_config;

		$log = new Logs();
		$where = "(module_id = ".$dolibase_config['module']['number'];
		$where.= " || module_name = '".$dolibase_config['module']['name']."'";
		$where.= ") AND object_id = ".$object_id;

		// Fetch logs
		if ($log->fetchAll(0, 0, 't.datec', 'DESC', '', '', $where))
		{
			$deltadateforserver = getServerTimeZoneInt('now');
			$deltadateforclient = ((int) $_SESSION['dol_tz'] + (int) $_SESSION['dol_dst']);
			$deltadateforuser   = round($deltadateforclient-$deltadateforserver);

			// Show logs
			echo '<table class="border" width="100%">';

			foreach ($log->lines as $line)
			{
				// Action
				echo '<tr><td class="titlefield">';
				echo $langs->trans("LogAction");
				echo '</td><td>';
				echo $langs->trans($line->action);
				echo '</td></tr>';

				// Date
				$datec = $log->db->jdate($line->datec);
				echo '<tr><td class="titlefield">';
				echo $langs->trans("LogDate");
				echo '</td><td>';
				echo dol_print_date($datec, 'dayhour');
				if ($deltadateforuser) {
					echo ' '.$langs->trans("CurrentHour").' &nbsp; / &nbsp; '.dol_print_date($datec+($deltadateforuser*3600),"dayhour").' &nbsp;'.$langs->trans("ClientHour");
				}
				echo '</td></tr>';

				// User
				$userstatic = new User($log->db);
				$userstatic->fetch($line->fk_user);
				echo '<tr><td class="titlefield">';
				echo $langs->trans("MadeBy");
				echo '</td><td>';
				echo $userstatic->getNomUrl(1, '', 0, 0, 0);
				echo '</td></tr>';
			}

			echo '</table>';
		}
		else
		{
			echo '<br>'.$langs->trans('NoLogsAvailable');
		}
	}
}