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

dolibase_include_once('core/class/form_page.php');
dolibase_include_once('core/class/query_builder.php');
require_once DOL_DOCUMENT_ROOT . '/core/lib/date.lib.php';

/**
 * LogPage class
 */

class LogPage extends FormPage
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
		$langs->load("log_page@".$dolibase_config['langs']['path']);

		// Add CSS files
		$this->appendToHead('<link rel="stylesheet" type="text/css" href="'.dolibase_buildurl('core/css/banner.css.php').'">'."\n");

		parent::__construct($page_title, $access_perm);
	}

	/**
	 * Load default actions
	 *
	 */
	protected function loadDefaultActions()
	{
		// Purge search criteria
		if (GETPOST("button_removefilter_x") || GETPOST("button_removefilter")) // Both test are required to be compatible with all browsers
		{
			$_POST = array();
			//$_GET  = array(); // id & ref should not be removed
		}
	}

	/**
	 * Generate tabs
	 *
	 * @param     $noheader     -1 or 0=Add tab header, 1=no tab header.
	 */
	protected function generateTabs($noheader = -1)
	{
		parent::generateTabs($noheader);
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

		//echo '<div class="underbanner clearboth"></div>';
	}

	/**
	 * Print logs
	 *
	 * @param     $object_id          object id
	 * @param     $object_element     object element
	 */
	public function printLogs($object_id, $object_element = '')
	{
		global $langs, $conf, $dolibase_config, $db;

		// Get parameters
		$search = array(
			'ds'   => GETPOSTDATE('ds'), // date start
			'de'   => GETPOSTDATE('de'), // date end
			'user' => GETPOST('user')
		);
		$sortfield = GETPOST('sortfield', 'alpha');
		$sortorder = GETPOST('sortorder', 'alpha');
		$optioncss = GETPOST('optioncss', 'alpha');
		$limit = GETPOST('limit') ? GETPOST('limit', 'int') : $conf->liste_limit;
		$page = GETPOST('page', 'int') ? GETPOST('page', 'int') : 0;
		$offset = $limit * $page;
		if (empty($sortfield)) $sortfield = "t.datec";
		if (empty($sortorder)) $sortorder = "DESC";

		// Set list parameters
		$param = '&id='.$object_id;
		foreach ($search as $key => $value) {
			if ($value != '') $param.= '&'.$key.'='.urlencode($value);
		}
		if ($optioncss != '') $param.= '&optioncss='.urlencode($optioncss);
		if ($limit > 0 && $limit != $conf->liste_limit) $param.= '&limit='.urlencode($limit);

		// Fetch logs
		$where = "(module_id = ".$dolibase_config['module']['number'];
		$where.= " || module_name = '".$dolibase_config['module']['name']."'";
		$where.= ") AND object_id = ".$object_id;
		if (! empty($object_element)) {
			$where.= " AND object_element = '".$object_element."'";
		}
		if ($search['ds']) $where .= " AND date(t.datec) >= date('".$db->idate($search['ds'])."')";
		if ($search['de']) $where .= " AND date(t.datec) <= date('".$db->idate($search['de'])."')";
		if ($search['user'] > 0) $where .= natural_search('t.fk_user', $search['user']);

		$qb = new QueryBuilder();
		$qb->select()->from('dolibase_logs', 't')->where($where)->orderBy($sortfield, $sortorder);

		$total = $qb->count();
		$qb->limit($limit+1, $offset)->execute(); // $limit+1 for list pagination (check print_barre_liste function)
		$count = $qb->count();

		// List
		echo '<form method="POST" action="'.$_SERVER["PHP_SELF"].'?id='.$object_id.'">';

		print_barre_liste('', $page, $_SERVER["PHP_SELF"], $param, $sortfield, $sortorder, '', $count, $total, '', 0, '', '', $limit);

		echo '<div class="div-table-responsive">';
		echo '<table class="liste" width="100%">';

		// List header
		echo '<tr class="liste_titre">';
		print_liste_field_titre("LogAction", $_SERVER["PHP_SELF"],"t.action","",$param,'align="left"',$sortfield,$sortorder);
		print_liste_field_titre("LogDate", $_SERVER["PHP_SELF"], "t.datec", "", $param, 'align="center"', $sortfield, $sortorder);
		print_liste_field_titre("LogUser", $_SERVER["PHP_SELF"], "t.fk_user", "", $param, 'align="left"', $sortfield, $sortorder);
		print_liste_field_titre('');
		echo "</tr>\n";

		echo '<tr class="liste_titre">';

		// Action
		echo '<td align="left" class="liste_titre"></td>';

		// Date
		echo '<td align="center" class="liste_titre">';
		echo $this->form->dateInput('ds', $search['ds'], 0);
		echo $this->form->dateInput('de', $search['de'], 0);
		echo '</td>';

		// User
		echo '<td align="left" class="liste_titre">';
		echo $this->form->select_dolusers($search['user'], 'user', 1, '', 0, '', '', 0, 0, 0, '', 0, '', 'maxwidth300');
		echo '</td>';

		// Search buttons
		echo '<td align="right" class="liste_titre">';
		echo $this->form->showFilterAndCheckAddButtons(0);
		echo '</td>';

		echo "</tr>\n";

		// Show logs
		if ($count > 0)
		{
			$deltadateforserver = getServerTimeZoneInt('now');
			$deltadateforclient = ((int) $_SESSION['dol_tz'] + (int) $_SESSION['dol_dst']);
			$deltadateforuser   = round($deltadateforclient-$deltadateforserver);
			$userstatic = new User($db);

			foreach ($qb->result($limit) as $row)
			{
				echo '<tr class="oddeven">';

				// Action
				echo '<td align="left">'.$langs->trans($row->action).'</td>';

				// Date
				echo '<td align="center">';
				echo dolibase_print_date($row->datec, 'dayhour');
				if ($deltadateforuser) {
					echo ' '.$langs->trans("CurrentHour").' / '.dol_print_date($db->jdate($row->datec)+($deltadateforuser*3600), "dayhour").' '.$langs->trans("ClientHour");
				}
				echo '</td>';

				// User
				echo '<td align="left" colspan="2">';
				$userstatic->fetch($row->fk_user);
				echo $userstatic->getNomUrl(1, '', 0, 0, 0);
				echo '</td>';

				echo "</tr>\n";
			}
		}
		else
		{
			echo '<tr><td colspan="4">'.$langs->trans('NoLogsAvailable').'</td></tr>';
		}

		// Close list
		echo '</table>';
		echo '</div>';

		echo '</form>';
	}
}
