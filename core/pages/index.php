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
dolibase_include_once('core/class/chart.php');

/**
 * IndexPage class
 */

class IndexPage extends FormPage
{
	/**
	 * @var int Statistics id (used to generate stats HTML id)
	 */
	protected $stats_id = 1;

	/**
	 * Constructor
	 * 
	 * @param     $page_title     HTML page title
	 * @param     $access_perm    Access permission
	 */
	public function __construct($page_title, $access_perm = '')
	{
		parent::__construct($page_title, $access_perm);
	}

	/**
	 * Generate page beginning
	 *
	 * @return  $this
	 */
	public function begin()
	{
		parent::begin();

		echo '<div class="fichecenter">';

		return $this;
	}

	/**
	 * Generate page end
	 *
	 */
	public function end()
	{
		echo '</div>';

		parent::end();
	}

	/**
	 * Opens a left section
	 *
	 * @return  $this
	 */
	public function openLeftSection()
	{
		echo '<div class="fichethirdleft">';

		return $this;
	}

	/**
	 * Close a left section
	 *
	 * @return  $this
	 */
	public function closeLeftSection()
	{
		echo '</div>';

		return $this;
	}

	/**
	 * Opens a right section
	 *
	 * @return  $this
	 */
	public function openRightSection()
	{
		echo '<div class="fichetwothirdright"><div class="ficheaddleft">';

		return $this;
	}

	/**
	 * Close a right section
	 *
	 * @return  $this
	 */
	public function closeRightSection()
	{
		echo '</div></div>';

		return $this;
	}

	/**
	 * Add a search form
	 *
	 * @param     $fields     an array of form fields, e.: array('Field 1' => 'field_name')
	 * @param     $url        form url
	 * @param     $title      form title
	 * @param     $summary    form summary
	 * @return    $this
	 */
	public function addSearchForm($fields, $url, $title = 'Search', $summary = '')
	{
		global $langs;

		echo '<form method="post" action="'.dol_buildpath($url, 1).'">';
		echo '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
		echo '<table class="noborder nohover" width="100%">';
		echo '<tr class="liste_titre"><td colspan="3">';
		$title = $langs->trans($title);
		echo (! empty($summary) ? $this->form->textwithpicto($title, $langs->trans($summary)) : $title);
		echo '</td></tr>';
		$count = 0;
		foreach ($fields as $key => $value) {
			$autofocus = ($count == 0 ? ' autofocus' : '');
			echo '<tr><td>'.$langs->trans($key);
			echo '</td><td><input type="text" class="flat inputsearch" name="'.$value.'" size="18"'.$autofocus.'></td>';
			if ($count == 0) {
				echo '<td class="noborderbottom" rowspan="'.count($fields).'"><input type="submit" value="'.$langs->trans('Search').'" class="button"></td>';
			}
			echo '</tr>';
			$count++;
		}
		echo "</table></form><br>\n";

		return $this;
	}

	/**
	 * Add a statistics graph
	 *
	 * @param     $table_name       Table name (without prefix)
	 * @param     $field_name       Table field name
	 * @param     $field_values     Table field values, e.: array(0 => 'Status 1', 1 => 'Status 2')
	 * @param     $graph_type       Type of graph ('pie', 'bars', 'lines')
	 * @param     $graph_title      Graph title
	 * @param     $pk_field_name    Table primary key name
	 * @return    $this
	 */
	public function addStatsGraph($table_name, $field_name, $field_values = array(), $graph_type = 'pie', $graph_title = 'Statistics', $pk_field_name = 'rowid')
	{
		global $db, $langs, $conf, $bc;

		$sql = "SELECT count(t.".$pk_field_name."), t.".$field_name;
		$sql.= " FROM ".MAIN_DB_PREFIX.$table_name." as t";
		$sql.= " GROUP BY t.".$field_name;

		$resql = $db->query($sql);

		if ($resql)
		{
			$num = $db->num_rows($resql);

			$i = 0;
			$total = 0;
			$totalinprocess = 0;
			$dataseries = array();
			$vals = array();

			while ($i < $num)
			{
				$row = $db->fetch_row($resql);
				if ($row)
				{
					$vals[$row[1]]   = $row[0];
					$totalinprocess += $row[0];
					$total          += $row[0];
				}
				$i++;
			}
			$db->free($resql);

			echo '<table class="noborder nohover" width="100%">';
			echo '<tr class="liste_titre"><td colspan="2">'.$langs->trans($graph_title).'</td></tr>'."\n";
			$var = true;

			foreach ($field_values as $key => $value)
			{
				$count = (isset($vals[$key]) ? (int) $vals[$key] : 0);

				if ($count > 0)
				{
					$label = $langs->trans($value);

					$dataseries[] = array($label, $count);

					if (! $conf->use_javascript_ajax)
					{
						$var = ! $var;
						echo "<tr ".$bc[$var].">";
						echo '<td>'.$label.'</td>';
						echo '<td align="right"><a href="list.php?'.$field_name.'='.$key.'">'.$count.'</a></td>';
						echo "</tr>\n";
					}
				}
			}

			if ($conf->use_javascript_ajax)
			{
				echo '<tr class="impair"><td align="center" colspan="2">';

				// Generate graph
				$graph = new Chart();
				$graph->generate($graph_type, $dataseries);
				$graph->setShowLegend(1); // force show legend
				$graph->setShowPercent(1);
				$graph->display('stats_'.($this->stats_id++));

				echo '</td></tr>';
			}

			echo '<tr class="liste_total"><td>'.$langs->trans('Total').'</td><td align="right">'.$total.'</td></tr>';
			echo '</table><br>';
		}
		else
		{
			dol_print_error($db);
		}

		return $this;
	}

	/**
	 * Add a statistics graph from predefined data
	 *
	 * @param     $data             Data to show
	 * @param     $legend           Legend array
	 * @param     $graph_type       Type of graph ('pie', 'bars', 'lines')
	 * @param     $graph_title      Graph title
	 * @return    $this
	 */
	public function addStatsGraphFromData($data, $legend = array(), $graph_type = 'pie', $graph_title = 'Statistics')
	{
		global $langs;

		echo '<table class="noborder nohover" width="100%">';
		echo '<tr class="liste_titre"><td colspan="2">'.$langs->trans($graph_title).'</td></tr>'."\n";
		echo '<tr class="impair"><td align="center" colspan="2">';

		// Generate graph
		$graph = new Chart();
		$graph->generate($graph_type, $data, $legend);
		$graph->setShowPercent(1);
		$graph->display('stats_'.($this->stats_id++));

		echo '</td></tr>';
		echo "</table><br>";

		return $this;
	}
}
