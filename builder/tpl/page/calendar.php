<?php

// Load Dolibase
include_once 'autoload.php';

// Load Dolibase Page class
dolibase_include_once('core/pages/calendar.php');

// Load Object class
${object_class_include}

$page = new CalendarPage('${page_title}', '${access_perms}', '${default_view}');

// Get parameters
// ...

// Init object
${object_init}

$page->begin();

// Calendar filter form
$page->addFilterForm(array());

// ...

// Show calendar
$page->printCalendar();

$page->end();
