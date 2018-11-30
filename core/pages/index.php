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
require_once DOL_DOCUMENT_ROOT . '/core/class/dolgraph.class.php';

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
	}

	/**
	 * Close a right section
	 *
	 */
	public function closeRightSection()
	{
		echo '</div></div>';
	}

	/**
	 * Add a search form
	 *
	 * @param     $fields     an array of form fields, e.: array('Field 1' => 'field_name')
	 * @param     $url        form url
	 * @param     $title      form title
	 * @param     $summary    form summary
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
				echo '<td class="noborderbottom" rowspan="'.count($fields).'"><input type="submit" value="'.$langs->trans("Search").'" class="button"></td>';
			}
			echo '</tr>';
			$count++;
		}
		echo "</table></form><br>\n";
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

				$graph = new DolGraph();
				$width = DolGraph::getDefaultGraphSizeForStats('width');
				$height = DolGraph::getDefaultGraphSizeForStats('height');
				$graph->SetData($dataseries);
				if (in_array($graph_type, array('bars', 'lines'))) {
					$graph->SetMaxValue($graph->GetCeilMaxValue());
					$graph->SetMinValue(min(0, $graph->GetFloorMinValue()));
				}
				$graph->setShowLegend(1);
				$graph->setShowPercent(1);
				$graph->SetType(array($graph_type));
				$graph->setWidth($width);
				$graph->setHeight($height);
				$graph->draw('stats_'.($this->stats_id++));
				echo $graph->show();

				echo '</td></tr>';
			}

			echo '<tr class="liste_total"><td>'.$langs->trans("Total").'</td><td align="right">'.$total.'</td></tr>';
			echo "</table><br>";
		}
		else
		{
			dol_print_error($db);
		}
	}

	/**
	 * Add a statistics graph from predefined data
	 *
	 * @param     $data             Data to show
	 * @param     $legend           Legend array
	 * @param     $graph_type       Type of graph ('pie', 'bars', 'lines')
	 * @param     $graph_title      Graph title
	 */
	public function addStatsGraphFromData($data, $legend = array(), $graph_type = 'pie', $graph_title = 'Statistics')
	{
		global $langs;

		echo '<table class="noborder nohover" width="100%">';
		echo '<tr class="liste_titre"><td colspan="2">'.$langs->trans($graph_title).'</td></tr>'."\n";
		echo '<tr class="impair"><td align="center" colspan="2">';

		// Generate graph
		$graph = new DolGraph();
		$width = DolGraph::getDefaultGraphSizeForStats('width');
		$height = DolGraph::getDefaultGraphSizeForStats('height');
		$show_legend = empty($legend) ? 0 : 1;
		if (! $graph->isGraphKo())
		{
			$graph->SetData($data);
			$graph->SetLegend($legend);
			if (in_array($graph_type, array('bars', 'lines'))) {
				$graph->SetMaxValue($graph->GetCeilMaxValue());
				$graph->SetMinValue(min(0, $graph->GetFloorMinValue()));
			}
			$graph->setShowLegend($show_legend);
			$graph->setShowPercent(1);
			$graph->SetType(array($graph_type));
			$graph->setWidth($width);
			$graph->setHeight($height);
			$graph->draw('stats_'.($this->stats_id++));
			echo $graph->show();
		}

		echo '</td></tr>';
		echo "</table><br>";
	}
}
