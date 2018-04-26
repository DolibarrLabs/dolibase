<?php

// Load Dolibarr environment (mandatory)
if (false === (@include '../../main.inc.php')) {  // From htdocs directory
	require '../../../main.inc.php'; // From "custom" directory
}

// Load Dolibase config file for this module (mandatory)
dol_include_once('/books/config.php');
// Load Dolibase SetupPage class
dolibase_include_once('/core/pages/setup.php');

// Create Setup Page using Dolibase
$page = new SetupPage();

$page->begin();

$page->addSubtitle("NumberingModels");

$page->printNumModels();

/*
$page->closeTable()->addBreakLine();

$page->addSubtitle("General Options");

$page->newOptionsTable();

global $conf;

$company_select = $page->form->select_company($conf->global->BOOKS_COMPANY_NAME, 'BOOKS_COMPANY_NAME');

$page->addOption('My company', $company_select, 'BOOKS_COMPANY_NAME');
*/

$page->end();