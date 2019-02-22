<?php

// Load Dolibase
require_once '../autoload.php';

// Load Dolibase SetupPage class
dolibase_include_once('/core/pages/setup.php');

// Create Setup Page using Dolibase
$page = new SetupPage();

$page->begin();

$page->addSubtitle("General Options");

$page->newOptionsTable();

$page->addSwitchOption("My first option", "MY_FIRST_MODULE_FIRST_OPTION");

$page->addSwitchOption("My second option", "MY_FIRST_MODULE_SECOND_OPTION", true);

$page->addTextOption("My text option", "MY_FIRST_MODULE_CONST");

$page->addNumberOption("My number option", "MY_FIRST_MODULE_NUMBER_OPTION");

$page->closeTable()->addLineBreak();

$page->addSubtitle("Other Options", 'title_setup.png');

$page->newOptionsTable();

$page->addListOption("My list option", "MY_FIRST_MODULE_LIST_OPTION", array('option1' => "Option 1", 'option2' => "Option 2"));

$page->addColorOption("My color option", "MY_FIRST_MODULE_COLOR_OPTION");

$page->end();
