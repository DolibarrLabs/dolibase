<?php

// Load Dolibase
include_once 'autoload.php';

// Load Dolibase Page class
dolibase_include_once('core/class/page.php');

// Load Query Builder class
dolibase_include_once('core/class/query_builder.php');

// Create Page using Dolibase
$page = new Page('Test Query Builder');

$page->begin();

$titles = array();
$queries = array();

// 1st query
$titles[]  = 'Select with order by & limit';
$queries[] = QueryBuilder::getInstance()
                         ->select('login, firstname, lastname')
                         ->from('user')
                         ->orderBy('rowid', 'ASC')
                         ->limit(5);

// 2nd query
$titles[]  = 'Insert';
$queries[] = QueryBuilder::getInstance()
                         ->insert('user', array('login' => 'axel', 'lastname' => 'AXeL'));

// 3rd query
$titles[]  = 'Update';
$queries[] = QueryBuilder::getInstance()
                         ->update('user', array('firstname' => 'Dev'))
                         ->where("login = 'axel'");

// 4th query
$titles[]  = 'Select where Like';
$queries[] = QueryBuilder::getInstance()
                         ->select('login, firstname, lastname')
                         ->from('user')
                         ->where("login LIKE 'axe%'");

// 5th query
$titles[]  = 'Delete';
$queries[] = QueryBuilder::getInstance()
                         ->delete('user')
                         //->where(array('login' => 'axel', 'lastname' => 'AXeL'))
                         ->where("login = 'axel'")
                         ->where("lastname = 'AXeL'")
                         ->orWhere("firstname = 'Dev'");

// 6th query
$titles[]  = 'Count';
$queries[] = QueryBuilder::getInstance()
                         ->select('count(*) as count')
                         ->from('user')
                         ->where("login = 'axel'");

// 7th query
$titles[]  = 'Left join';
$queries[] = QueryBuilder::getInstance()
                         ->select('p.ref as product, p.price as price, u.login as author')
                         ->from('product as p')
                         ->join('user as u', 'u.rowid = p.fk_user_author', 'left');

// 8th query
$titles[]  = 'Multi Left join';
$queries[] = QueryBuilder::getInstance()
                         ->select('p.ref as product, u.login as author, pl.ref as propal')
                         ->from('product as p')
                         ->join('user as u', 'u.rowid = p.fk_user_author', 'left')
                         ->join('propaldet as pd', 'pd.fk_product = p.rowid', 'left')
                         ->join('propal as pl', 'pl.rowid = pd.fk_propal', 'left');

// 9th query
$titles[]  = 'IQ Join with Select';
$queries[] = QueryBuilder::getInstance()
                         ->select('p.ref as product, u.login as author')
                         ->from(array('product as p', 'user as u'))
                         ->where('p.fk_user_author = u.rowid');

// 10th query
$titles[]  = 'Subquery'; 
$subquery  = QueryBuilder::getInstance()->select('p.fk_user_author')->from('product', 'p')->get();
$queries[] = QueryBuilder::getInstance()
                         ->select('u.login as user')
                         ->from('user', 'u')
                         ->where("u.rowid IN ($subquery)");

// Show queries
foreach ($queries as $i => $query) {
    echo '<h2><u>'.($i + 1).') '.$titles[$i].'</u></h2>';
    echo '<h3>query:</h3>'.$query->get();
    echo '<h3>result:</h3>'.array_to_table($query->result());
    echo '<h3>result count:</h3>'.$query->count();
    echo '<h3>affected rows:</h3>'.$query->affected();
}

$page->end();
