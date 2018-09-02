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
require_once DOL_DOCUMENT_ROOT.'/core/class/dolgraph.class.php';

/**
 * StatsPage class
 */

class StatsPage extends FormPage
{
	/**
	 * Constructor
	 * 
	 * @param     $page_title     HTML page title
	 * @param     $access_perm    Access permission
	 */
	public function __construct($page_title, $access_perm = '')
	{
		global $langs;

		// Load lang files
		$langs->load('other');

		parent::__construct($page_title, $access_perm);
	}

	/**
	 * Generate page begin
	 *
	 */
	public function begin()
	{
		parent::begin();

		echo '<div class="fichecenter">';
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
	 * Generate page end
	 *
	 */
	public function end()
	{
		echo '</div>';

		echo '<div style="clear:both"></div>';

		parent::end();
	}

	/**
	 * Opens a left section
	 *
	 */
	public function openLeftSection()
	{
		echo '<div class="fichethirdleft">';
	}

	/**
	 * Close a left section
	 *
	 */
	public function closeLeftSection()
	{
		echo '</div>';
	}

	/**
	 * Opens a right section
	 *
	 */
	public function openRightSection()
	{
		echo '<div class="fichetwothirdright"><div class="ficheaddleft">';

		echo '<table class="border" width="100%"><tr valign="top"><td align="center">';
	}

	/**
	 * Close a right section
	 *
	 */
	public function closeRightSection()
	{
		echo '</td></tr></table>';

		echo '</div></div>';
	}

	/**
	 * Add a filter form
	 *
	 * @param     $fields     an array of form fields, e.: array('Field label' => 'Field content')
	 * @param     $data       object data (to set years array)
	 * @param     $title      form title
	 * @param     $summary    form summary
	 */
	public function addFilterForm($fields, $data, $title = 'Filter', $summary = '')
	{
		global $langs;

		// Get parameters
		$nowyear = strftime("%Y", dol_now());
		$year = GETPOST('year') > 0 ? GETPOST('year') : $nowyear;

		// Set years array
		$arrayyears = array();
		foreach($data as $val) {
			if (! empty($val['year'])) {
				$arrayyears[$val['year']] = $val['year'];
			}
		}
		if (! count($arrayyears)) $arrayyears[$nowyear] = $nowyear;

		// Print form
		echo '<form method="POST" action="'.$_SERVER["PHP_SELF"].'">';
		echo '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
		echo '<table class="noborder nohover" width="100%">';
		echo '<tr class="liste_titre"><td colspan="3">';
		$title = $langs->trans($title);
		echo (! empty($summary) ? $this->form->textwithpicto($title, $langs->trans($summary)) : $title);
		echo '</td></tr>';
		// Fields
		foreach ($fields as $label => $content) {
			echo '<tr><td align="left">'.$langs->trans($label).'</td><td align="left">'.$content.'</td></tr>';
		}
		// Year field
		echo '<tr><td align="left">'.$langs->trans("Year").'</td><td align="left">';
		if (! in_array($year, $arrayyears)) $arrayyears[$year] = $year;
		if (! in_array($nowyear, $arrayyears)) $arrayyears[$nowyear] = $nowyear;
		arsort($arrayyears);
		echo $this->form->selectarray('year', $arrayyears, $year, 0);
		echo '</td></tr>';
		// Submit button
		echo '<tr><td align="center" colspan="2"><input type="submit" name="submit" class="button" value="'.$langs->trans("Refresh").'"></td></tr>';
		echo "</table></form><br>\n";
	}

	/**
	 * Add a statistics graph
	 *
	 * @param     $title          graph title
	 * @param     $data           grapĥ data
	 * @param     $suffix         graph filename suffix
	 */
	public function addGraph($title, $data, $suffix)
	{
		global $langs, $user, $conf;

		// Get parameters
		$nowyear = strftime("%Y", dol_now());
		$year = GETPOST('year') > 0 ? GETPOST('year') : $nowyear;
		$startyear = $year-1; // $year-2;
		$endyear = $year;

		// Create directory where to store graph png
		$dir = $conf->{$this->modulepart}->dir_temp;

		dol_mkdir($dir);

		// Set file name & url
		if (! $user->rights->societe->client->voir || $user->societe_id)
		{
			$filename = $dir.'/'.$this->modulepart.$suffix.'-'.$user->id.'-'.$year.'.png';
			$fileurl = DOL_URL_ROOT.'/viewimage.php?modulepart='.$this->modulepart.'stats&file='.$this->modulepart.$suffix.'-'.$user->id.'-'.$year.'.png';
		}
		else
		{
			$filename = $dir.'/'.$this->modulepart.$suffix.'-'.$year.'.png';
			$fileurl = DOL_URL_ROOT.'/viewimage.php?modulepart='.$this->modulepart.'stats&file='.$this->modulepart.$suffix.'-'.$year.'.png';
		}

		// Generate graph
		$graph = new DolGraph();
		$WIDTH = DolGraph::getDefaultGraphSizeForStats('width');
		$HEIGHT = DolGraph::getDefaultGraphSizeForStats('height');
		if (! $graph->isGraphKo())
		{
			$i = $startyear;
			$legend = array();
			while ($i <= $endyear)
			{
				$legend[] = $i;
				$i++;
			}
			$graph->SetData($data);
			$graph->SetLegend($legend);
			$graph->SetMaxValue($graph->GetCeilMaxValue());
			$graph->SetMinValue(min(0,$graph->GetFloorMinValue()));
			$graph->SetWidth($WIDTH);
			$graph->SetHeight($HEIGHT);
			//$graph->SetYLabel($langs->trans("YLabel"));
			$graph->SetShading(3);
			$graph->SetHorizTickIncrement(1);
			$graph->SetPrecisionY(0);
			$graph->mode = 'depth';
			$graph->SetTitle($langs->trans($title));

			$graph->draw($filename, $fileurl);

			echo $graph->show();
		}
	}
}