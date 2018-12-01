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

dolibase_include_once('/core/class/form_page.php');
require_once DOL_DOCUMENT_ROOT . '/core/lib/date.lib.php';

/**
 * CalendarPage class
 */

class CalendarPage extends FormPage
{
	/**
	 * @var string Page parameters
	 */
	protected $params = array();
	/**
	 * @var string Calendar template files
	 */
	protected $template_files = array();


	/**
	 * Constructor
	 * 
	 * @param     $page_title     HTML page title
	 * @param     $access_perm    Access permission
	 * @param     $default_view   Default calendar view, possible values: 'day', 'week', 'month'
	 */
	public function __construct($page_title, $access_perm = '', $default_view = '')
	{
		global $langs, $dolibase_config;

		// Load lang files
		$langs->load('calendar_page@'.$dolibase_config['langs']['path']);

		// Set page attributes
		$this->params = array(
			'optioncss' => GETPOST('optioncss', 'alpha'),
			'action' => GETPOST('action', 'alpha'),
			'year' => GETPOST('year', 'int') ? GETPOST('year', 'int') : date('Y'),
			'month' => GETPOST('month', 'int') ? GETPOST('month', 'int') : date('m'),
			'day' => GETPOST('day', 'int') ? GETPOST('day', 'int') : date('d'),
			'wich_week' => GETPOST('wich_week', 'alpha'),
			'dateselect' => GETPOST('dateselect')
		);

		if (empty($this->params['action']) && ! empty($default_view)) {
			$this->params['action'] = 'show_'.$default_view;
		}

		if (! empty($this->params['dateselect'])) {
			$day   = GETPOST('dateselectday', 'int');
			$month = GETPOST('dateselectmonth', 'int');
			$year  = GETPOST('dateselectyear', 'int');
			$this->params['dateselect'] = dol_mktime(0, 0, 0, $month, $day, $year);
			$this->params['day']   = $day;
			$this->params['month'] = $month;
			$this->params['year']  = $year;
		}

		parent::__construct($page_title, $access_perm);
	}

	/**
	 * Generate tabs
	 *
	 * @param     $noheader     -1 or 0=Add tab header, 1=no tab header.
	 */
	protected function generateTabs($noheader = 0)
	{
		if ($this->params['optioncss'] != 'print')
		{
			$url = str_replace(DOL_URL_ROOT, '', $_SERVER["PHP_SELF"]);

			$this->addTab('DayView', $url.'?action=show_day', ($this->params['action'] == 'show_day' || empty($this->params['action']) ? true : false));
			$this->addTab('WeekView', $url.'?action=show_week', ($this->params['action'] == 'show_week' ? true : false));
			$this->addTab('MonthView', $url.'?action=show_month', ($this->params['action'] == 'show_month' ? true : false));

			parent::generateTabs($noheader);
		}
	}

	/**
	 * Add a filter form
	 *
	 * @param     $fields     an array of form fields, e.: array('Field label' => 'Field content')
	 */
	public function addFilterForm($fields)
	{
		if ($this->params['optioncss'] != 'print')
		{
			global $langs, $conf;

			// Print form
			echo '<form class="listactionsfilter" action="' . $_SERVER["PHP_SELF"] . '" method="post">';
			echo '<input type="hidden" name="token" value="' . $_SESSION ['newtoken'] . '">';
			echo '<input type="hidden" name="year" value="' . $this->params['year'] . '">';
			echo '<input type="hidden" name="month" value="' . $this->params['month'] . '">';
			echo '<input type="hidden" name="day" value="' . $this->params['day'] . '">';
			echo '<input type="hidden" name="action" value="' . $this->params['action'] . '">';

			echo '<div class="fichecenter">';

			if (! empty($conf->browser->phone)) echo '<div class="fichehalfleft">';
			else echo '<table class="nobordernopadding" width="100%"><tr><td class="borderright">';
			echo '<table class="nobordernopadding centpercent">';

			// Fields
			foreach ($fields as $label => $content) {
				echo '<tr><td class="nowrap">'.$langs->trans($label).'</td><td class="nowrap">'.$content.'</td></tr>';
			}

			echo '</table>';
			if (! empty($conf->browser->phone)) echo '</div>';
			else echo '</td>';

			// Buttons
			if (! empty($conf->browser->phone)) echo '<div class="fichehalfright">';
			else echo '<td align="center" valign="middle" class="nowrap">';
			echo '<table class="centpercent"><tr><td align="center">';
			echo '<div class="formleftzone">';

			// Refresh
			echo '<input type="submit" class="butAction minwidth100" name="refresh" value="' . $langs->trans("Refresh") . '">';

			// Print
			$qs = dol_escape_htmltag($_SERVER["QUERY_STRING"]);
			foreach($_POST as $key => $value) {
				if ($key != 'token' && ! is_array($value)) $qs.= '&'.$key.'='.urlencode($value);
			}
			echo '<a class="butAction minwidth100" href="'.dol_escape_htmltag($_SERVER["PHP_SELF"]).'?'.$qs.(empty($qs) ? '' : '&').'optioncss=print" target="_blank">' . $langs->trans("Print") . '</a>';

			echo '</div>';
			echo '</td></tr>';
			echo '</table>';
			if (! empty($conf->browser->phone)) echo '</div>';
			else echo '</td></tr></table>';

			// Close fichecenter & form
			echo '</div>';
			echo '<div class="clearboth"></div>';
			echo '</form>';

			dol_fiche_end();
			$this->add_fiche_end = false;
		}
	}

	/**
	 * Show navigation bar
	 *
	 * @param     $nav     navigation bar HTML
	 */
	protected function showNavBar($nav)
	{
		$form = '';

		if ($this->params['optioncss'] != 'print')
		{
			global $langs;

			// Date select form
			$form.= '<form action="'.$_SERVER["PHP_SELF"].'" method="post">';
			$form.= '<input type="hidden" name="token" value="' . $_SESSION ['newtoken'] . '">';
			$form.= '<input type="hidden" name="action" value="' . $this->params['action'] . '">';
			$form.= $this->form->select_date($this->params['dateselect'], 'dateselect', 0, 0, 1, '', 1, 0, 1);
			$form.= '<input type="submit" class="button" value="'.$langs->trans("Refresh").'">';
			$form.= '</form>';
		}

		echo load_fiche_titre($form, $nav, '', 0, 0, 'tablelistofcalendars');
	}

	/**
	 * Show calendar events
	 *
	 * @param   int		$day             Day
	 * @param   int		$month           Month
	 * @param   int		$year            Year
	 * @param   int		$minheight       Minimum height for each event. 60px by default.
	 */
	protected function showEvents($day, $month, $year, $minheight = 60)
	{
		$ymd = sprintf("%04d", $year).sprintf("%02d", $month).sprintf("%02d", $day);
		$curtime = dol_mktime(0, 0, 0, $month, $day, $year);

		// Line with title of day
		echo '<div id="'.$this->rights_class.'_'.$ymd.'">';
		echo '<table class="nobordernopadding" width="100%">'."\n";

		echo '<tr><td align="left" class="nowrap">';
		echo '<a href="'.$_SERVER["PHP_SELF"].'?action=show_day&day='.str_pad($day, 2, "0", STR_PAD_LEFT).'&month='.str_pad($month, 2, "0", STR_PAD_LEFT).'&year='.$year.'">';
		echo dol_print_date($curtime, ($this->params['action'] == 'show_month' ? '%d' : 'daytextshort'));
		echo '</a>';
		echo '</td></tr>'."\n";

		// Line with td contains all div of each event
		echo '<tr height="'.$minheight.'"><td valign="top" colspan="2" class="sortable">';
		echo '<div class="centpercent">';

		$template_params = array(
			'day'   => $day,
			'month' => $month,
			'year'  => $year
		);

		foreach ($this->template_files as $template) {
			$this->showTemplate($template, false, false, $template_params);
		}

		echo '</div>';
		echo '</td></tr>';

		echo '</table></div>'."\n";
	}

	/**
	 * Print a calendar
	 *
	 * @param     $template_files     Template files array
	 */
	public function printCalendar($template_files = array())
	{
		global $langs, $conf;

		// Get parameters
		$now = dol_now();
		$nowarray = dol_getdate($now);
		$nowyear = $nowarray['year'];
		$nowmonth = $nowarray['mon'];
		$nowday = $nowarray['mday'];
		$year = $this->params['year'];
		$month = $this->params['month'];
		$day = $this->params['day'];

		// Set template files for events
		$this->template_files = $template_files;

		// Set url parameters for navigation bar
		$param = '';
		foreach ($this->params as $key => $value) {
			if (! in_array($key, array('day', 'month', 'year', 'wich_week', 'dateselect'))) $param.= '&'.$key.'='.$value;
		}

		/*
		 * Month view
		 */

		if ($this->params['action'] == 'show_month')
		{
			$prev = dol_get_prev_month($month, $year);
			$prev_year  = $prev['year'];
			$prev_month = $prev['month'];
			$next = dol_get_next_month($month, $year);
			$next_year  = $next['year'];
			$next_month = $next['month'];
			$max_day_in_prev_month = date("t", dol_mktime(0, 0, 0, $prev_month, 1, $prev_year));  // Nb of days in previous month
			$max_day_in_month = date("t", dol_mktime(0, 0, 0, $month, 1, $year));                 // Nb of days in next month
			// tmpday is a negative or null cursor to know how many days before the 1st to show on month view (if tmpday=0, 1st is monday)
			$tmpday = -date("w", dol_mktime(0, 0, 0, $month, 1, $year, true))+2; // date('w') is 0 fo sunday
			$tmpday+= ((isset($conf->global->MAIN_START_WEEK)?$conf->global->MAIN_START_WEEK:1)-1);
			if ($tmpday >= 1) $tmpday -= 7; // If tmpday is 0 we start with sunday, if -6, we start with monday of previous week.
			// Define firstdaytoshow and lastdaytoshow (warning: lastdaytoshow is last second to show + 1)
			$firstdaytoshow = dol_mktime(0, 0, 0, $prev_month, $max_day_in_prev_month+$tmpday, $prev_year);
			$next_day = 7 - ($max_day_in_month+1-$tmpday) % 7;
			if ($next_day < 6) $next_day+= 7;
			$lastdaytoshow = dol_mktime(0, 0, 0, $next_month, $next_day, $next_year);

			$nav = "<a href=\"?year=".$prev_year."&amp;month=".$prev_month.$param."\">".img_previous($langs->trans("Previous"), 'class="valignbottom"')."</a>\n";
			$nav.= "<span id=\"month_name\">".dol_print_date(dol_mktime(0, 0, 0, $month, 1, $year), "%b %Y");
			$nav.= "</span>\n";
			$nav.= "<a href=\"?year=".$next_year."&amp;month=".$next_month.$param."\">".img_next($langs->trans("Next"), 'class="valignbottom"')."</a>\n";
			$nav.= " &nbsp; (<a href=\"?year=".$nowyear."&amp;month=".$nowmonth.$param."\">".$langs->trans("Today")."</a>)";

			// Show navigation bar
			$this->showNavBar($nav);

			// Show calendar
			echo '<table width="100%" class="noborder nocellnopadd cal_pannel cal_month">';
			echo '<tr class="liste_titre">';
			$i = 0;
			while ($i < 7)
			{
				echo '<td align="center"'.($i == 0 ? ' colspan="2"': '').'>';
				$numdayinweek = (($i+(isset($conf->global->MAIN_START_WEEK)?$conf->global->MAIN_START_WEEK:1)) % 7);
				if (! empty($conf->dol_optimize_smallscreen))
				{
					$labelshort = array(0 => 'SundayMin', 1 => 'MondayMin', 2 => 'TuesdayMin', 3 => 'WednesdayMin', 4 => 'ThursdayMin', 5 => 'FridayMin', 6 => 'SaturdayMin');
					echo $langs->trans($labelshort[$numdayinweek]);
				}
				else echo $langs->trans("Day".$numdayinweek);
				echo "</td>\n";
				$i++;
			}
			echo "</tr>\n";

			$todayarray = dol_getdate($now, 'fast');
			$todaytms = dol_mktime(0, 0, 0, $todayarray['mon'], $todayarray['mday'], $todayarray['year']);

			// In loops, tmpday contains day nb in current month (can be zero or negative for days of previous month)
			for ($iter_week = 0; $iter_week < 6 ; $iter_week++)
			{
				echo "<tr>\n";
				for ($iter_day = 0; $iter_day < 7; $iter_day++)
				{
					/* Show days before the beginning of the current month (previous month) */
					if ($tmpday <= 0)
					{
						$style = 'cal_other_month cal_past';
						if ($iter_day == 6) $style.=' cal_other_month_right';
						echo '<td class="'.$style.' nowrap" width="14%" valign="top"'.($iter_day == 0 ? ' colspan="2"': '').'>';
						$this->showEvents($max_day_in_prev_month + $tmpday, $prev_month, $prev_year);
						echo "</td>\n";
					}
					/* Show days of the current month */
					else if ($tmpday <= $max_day_in_month)
					{
						$curtime = dol_mktime(0, 0, 0, $month, $tmpday, $year);
						$style = 'cal_current_month';
						if ($iter_day == 6) $style.= ' cal_current_month_right';
						$today = 0;
						if ($todayarray['mday'] == $tmpday && $todayarray['mon'] == $month && $todayarray['year'] == $year) $today = 1;
						if ($today) $style = 'cal_today';
						if ($curtime < $todaytms) $style.= ' cal_past';
						echo '<td class="'.$style.' nowrap" width="14%" valign="top"'.($iter_day == 0 ? ' colspan="2"': '').'>';
						$this->showEvents($tmpday, $month, $year);
						echo "</td>\n";
					}
					/* Show days after the current month (next month) */
					else
					{
						$style = 'cal_other_month';
						if ($iter_day == 6) $style.= ' cal_other_month_right';
						echo '<td class="'.$style.' nowrap" width="14%" valign="top"'.($iter_day == 0 ? ' colspan="2"': '').'>';
						$this->showEvents($tmpday - $max_day_in_month, $next_month, $next_year);
						echo "</td>\n";
					}
					$tmpday++;
				}
				echo "</tr>\n";
			}
			echo "</table>\n";
		}

		/*
		 * Week view
		 */

		else if ($this->params['action'] == 'show_week')
		{
			// wich week
			if ($this->params['wich_week'] == 'previous')
			{
				$prev = dol_get_first_day_week($day, $month, $year);
				$year  = $prev['prev_year'];
				$month = $prev['prev_month'];
				$day   = $prev['prev_day'];
			}
			else if ($this->params['wich_week'] == 'next')
			{
				$week = date('W');
				$next = dol_get_next_week($day, $week, $month, $year);
				$year  = $next['year'];
				$month = $next['month'];
				$day   = $next['day'];
			}

			$prev = dol_get_first_day_week($day, $month, $year);
			$prev_year  = $prev['prev_year'];
			$prev_month = $prev['prev_month'];
			$prev_day   = $prev['prev_day'];
			$first_day  = $prev['first_day'];
			$first_month= $prev['first_month'];
			$first_year = $prev['first_year'];
			$week = $prev['week'];
			$day = (int) $day;
			$next = dol_get_next_week($first_day, $week, $first_month, $first_year);
			$next_year  = $next['year'];
			$next_month = $next['month'];
			$next_day   = $next['day'];

			// Define firstdaytoshow and lastdaytoshow (warning: lastdaytoshow is last second to show + 1)
			$firstdaytoshow = dol_mktime(0, 0, 0, $first_month, $first_day, $first_year);
			$lastdaytoshow = dol_time_plus_duree($firstdaytoshow, 7, 'd');
			$max_day_in_month = date("t", dol_mktime(0, 0, 0, $month, 1, $year));
			$tmpday = $first_day;

			$nav = "<a href=\"?year=".$prev_year."&amp;month=".$prev_month."&amp;day=".$prev_day.$param."\">".img_previous($langs->trans("Previous"), 'class="valignbottom"')."</a>\n";
			$nav.= "<span id=\"month_name\">".dol_print_date(dol_mktime(0, 0, 0, $first_month, $first_day, $first_year),"%Y").", ".$langs->trans("Week")." ".$week;
			$nav.= "</span>\n";
			$nav.= "<a href=\"?year=".$next_year."&amp;month=".$next_month."&amp;day=".$next_day.$param."\">".img_next($langs->trans("Next"), 'class="valignbottom"')."</a>\n";
			$nav.= " &nbsp; (<a href=\"?year=".$nowyear."&amp;month=".$nowmonth."&amp;day=".$nowday.$param."\">".$langs->trans("Today")."</a>)";

			// Show navigation bar
			$this->showNavBar($nav);

			// Show calendar
			echo '<table width="100%" class="noborder nocellnopadd cal_pannel cal_month">';
			echo '<tr class="liste_titre">';
			$i = 0;
			while ($i < 7)
			{
				$curtime = strtotime('+'.$i.' day', $firstdaytoshow); //dol_time_plus_duree($firstdaytoshow, $i, 'd'); // this function is bugged on week view (when week start from Sunday 29 Oct 2017)
				$tmparray = dol_getdate($curtime, true);
				$tmpday = $tmparray['mday'];
				$tmpmonth = $tmparray['mon'];
				$tmpyear = $tmparray['year'];

				echo '<td align="center"'.($i == 0 ? ' colspan="2"': '').'>';
				echo $langs->trans("Day".(($i+(isset($conf->global->MAIN_START_WEEK)?$conf->global->MAIN_START_WEEK:1)) % 7));
				echo "</td>\n";
				$i++;
			}
			echo "</tr>\n";

			echo "<tr>\n";

			for ($iter_day = 0; $iter_day < 7; $iter_day++)
			{
				// Show days of the current week
				$curtime = strtotime('+'.$iter_day.' day', $firstdaytoshow); //dol_time_plus_duree($firstdaytoshow, $iter_day, 'd'); // this function is bugged on week view (when week start from Sunday 29 Oct 2017)
				$tmparray = dol_getdate($curtime, true);
				$tmpday = $tmparray['mday'];
				$tmpmonth = $tmparray['mon'];
				$tmpyear = $tmparray['year'];

				$style = 'cal_current_month';
				if ($iter_day == 6) $style.= ' cal_other_month_right';
				$today = 0;
				$todayarray = dol_getdate($now, 'fast');
				if ($todayarray['mday'] == $tmpday && $todayarray['mon'] == $tmpmonth && $todayarray['year'] == $tmpyear) $today = 1;
				if ($today) $style = 'cal_today';

				echo '<td class="'.$style.'" width="14%" valign="top"'.($iter_day == 0 ? ' colspan="2"': '').'>';
				$this->showEvents($tmpday, $tmpmonth, $tmpyear, 300);
				echo "</td>\n";
			}
			echo "</tr>\n";
			echo "</table>\n";
		}

		/*
		 * Day view
		 */

		else if ($this->params['action'] == 'show_day' || empty($this->params['action']))
		{
			$prev = dol_get_prev_day($day, $month, $year);
			$prev_year  = $prev['year'];
			$prev_month = $prev['month'];
			$prev_day   = $prev['day'];
			$next = dol_get_next_day($day, $month, $year);
			$next_year  = $next['year'];
			$next_month = $next['month'];
			$next_day   = $next['day'];

			// Define firstdaytoshow and lastdaytoshow (warning: lastdaytoshow is last second to show + 1)
			$firstdaytoshow = dol_mktime(0, 0, 0, $prev_month, $prev_day, $prev_year);
			$lastdaytoshow = dol_mktime(0, 0, 0, $next_month, $next_day, $next_year);

			$nav = "<a href=\"?year=".$prev_year."&amp;month=".$prev_month."&amp;day=".$prev_day.$param."\">".img_previous($langs->trans("Previous"), 'class="valignbottom"')."</a>\n";
			$nav.= "<span id=\"month_name\">".dol_print_date(dol_mktime(0, 0, 0, $month, $day, $year), "daytextshort");
			$nav.= "</span>\n";
			$nav.= "<a href=\"?year=".$next_year."&amp;month=".$next_month."&amp;day=".$next_day.$param."\">".img_next($langs->trans("Next"), 'class="valignbottom"')."</a>\n";
			$nav.= " &nbsp; (<a href=\"?year=".$nowyear."&amp;month=".$nowmonth."&amp;day=".$nowday.$param."\">".$langs->trans("Today")."</a>)";

			// Show navigation bar
			$this->showNavBar($nav);

			// Show calendar
			$style = 'cal_current_month cal_current_month_oneday';
			$today = 0;
			$todayarray = dol_getdate($now, 'fast');
			if ($todayarray['mday'] == $day && $todayarray['mon'] == $month && $todayarray['year'] == $year) $today = 1;
			//if ($today) $style = 'cal_today';

			$timestamp = dol_mktime(0, 0, 0, $month, $day, $year);
			$arraytimestamp = dol_getdate($timestamp);
			echo '<table width="100%" class="noborder nocellnopadd cal_pannel cal_month">';
			echo '<tr class="liste_titre">';
			echo '<td align="center" colspan="2">';
			echo $langs->trans("Day".$arraytimestamp['wday']);
			echo "</td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo '<td class="'.$style.'" width="14%" valign="top" colspan="2">';
			$this->showEvents($day, $month, $year, 300);
			echo "</td>\n";
			echo "</tr>\n";
			echo "</table>\n";
		}
	}
}
