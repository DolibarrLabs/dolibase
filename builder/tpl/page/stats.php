<?php

// Load Dolibase
include_once 'autoload.php';

// Load Dolibase Page class
dolibase_include_once('core/pages/stats.php');

// Load Object class
${object_class_include}

// Load Dolibase Stats class
dolibase_include_once('core/class/custom_stats.php');

// Create Page using Dolibase
$page = new StatsPage('${page_title}', '${access_perms}');

// Get parameters
$nowyear = strftime('%Y', dol_now());
$year = GETPOST('year') > 0 ? GETPOST('year') : $nowyear;
$startyear = $year-1; // $year-2;
$endyear = $year;

// Get filter parameters
$filter = array();
// ...

// Adjust query
$where = '1=1';
// ...

// Init object(s)
${object_init}
$stats = new CustomStats($object->table_element, '', $where, '${date_field}', '${amount_field}');

// Set main subtitle
$page->setMainSubtitle('Statistics');

// Set tabs
$page->addTab('ByMonthYear', '/${module_folder}/${page_name}', true);

$page->begin();

$page->openLeftSection();

// Get data (by year)
$data = $stats->getAllByYear();

// Filter form
$filter_fields = array();

$page->addFilterForm($filter_fields, $data, 'Filter');

$page->addLineBreak();

// Data table
$page->openTable(array(
	array('name' => 'Year', 'attr' => 'align="center"'),
	array('name' => 'Number', 'attr' => 'align="center"'),
	array('name' => '%', 'attr' => 'align="center"'),
	array('name' => 'TotalAmount', 'attr' => 'align="center"'),
	array('name' => '%', 'attr' => 'align="center"'),
));

$oldyear = 0;
$odd = true;
foreach ($data as $val)
{
	$year = $val['year'];

	// If we have empty year
	while (! empty($year) && $oldyear > $year+1)
	{
		$oldyear--;
		$odd = ! $odd;
		$page->openRow($odd);

		$page->addColumn('<a href="'.$_SERVER["PHP_SELF"].'?year='.$oldyear.'">'.$oldyear.'</a>', 'align="center"');
		$page->addColumn('0', 'align="center"');
		$page->addColumn('', 'align="center"');
		$page->addColumn('0', 'align="center"');
		$page->addColumn('', 'align="center"');

		$page->closeRow();
	}

	$odd = ! $odd;
	$page->openRow($odd);

	// Year
	$page->addColumn('<a href="'.$_SERVER["PHP_SELF"].'?year='.$year.'">'.$year.'</a>', 'align="center"');

	// Number
	$page->addColumn($val['nb'], 'align="center"');

	// %
	$page->addColumn(round($val['nb_diff']), 'align="center" class="'.(($val['nb_diff'] >= 0) ? 'color-green':'color-red').'"');

	// Amount
	$total = price2num($val['total'], 'MT');
	$page->addColumn(price_with_currency($total), 'align="center"');

	// %
	$page->addColumn(round($val['total_diff']), 'align="center" class="'.(($val['total_diff'] >= 0) ? 'color-green':'color-red').'"');

	$page->closeRow();

	$oldyear = $year;
}

$page->closeTable();

$page->closeLeftSection();

$page->openRightSection();

// Add Number graph
$data = $stats->getNbByMonthWithPrevYear($endyear, $startyear);
$page->addGraph('NumberByMonth', $data, 'nbinyear');
$page->addLineBreak();

// Add Amount graph
$data = $stats->getAmountByMonthWithPrevYear($endyear, $startyear, 0);
$page->addGraph('AmountByMonth', $data, 'amountinyear');

$page->closeRightSection();

$page->end();
