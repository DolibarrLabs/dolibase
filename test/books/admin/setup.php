<?php
/* <one line to give the program's name and a brief idea of what it does.>
 * Copyright (C) <year>  <name of author>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * \file    admin/setup.php
 * \ingroup mymodule
 * \brief   Example module setup page.
 *
 * Put detailed description here.
 */

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