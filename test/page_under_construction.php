<?php

// Load Dolibase
include_once 'autoload.php';

// Load Dolibase Page class
dolibase_include_once('core/class/page.php');

// Create Page using Dolibase
$page = new Page('Page Under Construction');

$page->begin()
     ->underConstruction()
     ->end();
