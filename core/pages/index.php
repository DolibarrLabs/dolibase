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

dolibase_include_once('/core/class/form_page.php');

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
	 * Generate page begin
	 *
	 */
	public function begin()
	{
		parent::begin();

		print '<div class="fichecenter">';
	}

	/**
	 * Generate page end
	 *
	 */
	public function end()
	{
		print '</div>';

		parent::end();
	}

	/**
	 * Opens a left section
	 *
	 */
	public function openLeftSection()
	{
		print '<div class="fichethirdleft">';
	}

	/**
	 * Close a left section
	 *
	 */
	public function closeLeftSection()
	{
		print '</div>';
	}

	/**
	 * Opens a right section
	 *
	 */
	public function openRightSection()
	{
		print '<div class="fichetwothirdright"><div class="ficheaddleft">';
	}

	/**
	 * Close a right section
	 *
	 */
	public function closeRightSection()
	{
		print '</div></div>';
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

		print '<form method="post" action="'.dol_buildpath($url, 1).'">';
		print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
		print '<table class="noborder nohover" width="100%">';
		print '<tr class="liste_titre"><td colspan="3">';
		$title = $langs->trans($title);
		print (! empty($summary) ? $this->form->textwithpicto($title, $langs->trans($summary)) : $title);
		print '</td></tr>';
		$count = 0;
		foreach ($fields as $key => $value) {
			$autofocus = ($count == 0 ? ' autofocus' : '');
			print '<tr><td>'.$langs->trans($key);
			print '</td><td><input type="text" class="flat inputsearch" name="'.$value.'" size="18"'.$autofocus.'></td>';
			if ($count == 0) {
				print '<td class="noborderbottom" rowspan="'.count($fields).'"><input type="submit" value="'.$langs->trans("Search").'" class="button"></td>';
			}
			print '</tr>';
			$count++;
		}
		print "</table></form><br>\n";
	}

	/**
	 * Add a statistics graph
	 *
	 * @param     $table_name       Table name (without prefix)
	 * @param     $field_name       Table field name
	 * @param     $field_values     Table field values, e.: array(0 => 'Status 1', 1 => 'Status 2')
	 * @param     $graph_type       Type of graph ('pie', 'barline')
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

		    print '<table class="noborder nohover" width="100%">';
		    print '<tr class="liste_titre"><td colspan="2">'.$langs->trans($graph_title).'</td></tr>'."\n";
		    $var = true;

		    if ($graph_type == 'barline')
		    {
			    $xlabel = array();
			    $i = 0;
			}

		    foreach ($field_values as $key => $value)
		    {
		    	$count = (isset($vals[$key]) ? (int) $vals[$key] : 0);

		    	if ($count > 0)
		    	{
		    		$label = $langs->trans($value);

		    		if ($graph_type == 'barline')
		    		{
			    		$xlabel[]     = array($i, $label);
			    		$dataseries[] = array($i, $count);
			    		$i++;
			    	}
			    	else
			    	{
			        	$dataseries[] = array('label' => $label, 'data' => $count);
			        }

			        if (! $conf->use_javascript_ajax)
			        {
			            $var = ! $var;
			            print "<tr ".$bc[$var].">";
			            print '<td>'.$label.'</td>';
			            print '<td align="right"><a href="list.php?'.$field_name.'='.$value.'">'.$count.'</a></td>';
			            print "</tr>\n";
			        }
			    }
		    }

		    if ($conf->use_javascript_ajax)
		    {
		        print '<tr class="impair"><td align="center" colspan="2">';
		        if ($graph_type == 'barline')
		        {
		        	$data = array('series' => array(array('label' => $field_name, 'data' => $dataseries)),
		        				  'xlabel' => $xlabel
		        				);
		        	$showlegend = 0;
		        }
		        else
		        {
		        	$data = array('series' => $dataseries);
		        	$showlegend = 1;
		        }
		        dol_print_graph('stats_'.($this->stats_id++), 300, 180, $data, $showlegend, $graph_type, 1);
		        print '</td></tr>';
		    }
		    
		    print '<tr class="liste_total"><td>'.$langs->trans("Total").'</td><td align="right">'.$total.'</td></tr>';
		    print "</table><br>";
		}
		else
		{
		    dol_print_error($db);
		}
	}
}