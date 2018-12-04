<?php

// Load Dolibase
include_once 'autoload.php';

// Load Dolibase Page class
dolibase_include_once('/core/pages/list.php');

// Load object class
dolibase_include_once('/core/class/logs.php');

// Load Dolibase QueryBuilder class
dolibase_include_once('/core/class/query_builder.php');

// Create Page using Dolibase
$page = new ListPage("DolibaseLogs", '$user->rights->dolibase_logs->read');

// Get parameters
global $conf, $langs;
$sortorder = GETPOST('sortorder', 'alpha') ? GETPOST('sortorder', 'alpha') : 'DESC';
$sortfield = GETPOST('sortfield', 'alpha') ? GETPOST('sortfield', 'alpha') : 't.datec';
$limit = GETPOST('limit') ? GETPOST('limit', 'int') : $conf->liste_limit;
$offset = $limit * (GETPOST('page', 'int') ? GETPOST('page', 'int') : 0);
$action = GETPOST('action', 'alpha');
$confirm = GETPOST('confirm', 'alpha');

// search parameters
$search = array();
$search['log_action'] = GETPOST('log_action');
$search['mod_name'] = GETPOST('mod_name');
$search['mod_id'] = GETPOST('mod_id');
$search['obj_id'] = GETPOST('obj_id');
$search['obj_element'] = GETPOST('obj_element');
$search['ds'] = GETPOSTDATE('ds'); // date start
$search['de'] = GETPOSTDATE('de'); // date end
$search['user'] = GETPOST('user');

// Init objects
$log = new Logs();
$userstatic = new User($log->db);

// Actions
if ($action == 'purge') {
	$page->askForConfirmation($_SERVER["PHP_SELF"], 'Purge', 'ConfirmPurge', 'confirm_purge');
}
else if ($action == 'confirm_purge' && $confirm == 'yes') {
	$result = $log->deleteAll();
	if ($result > 0) {
		setEventMessage($langs->trans("PurgeSuccess"), 'mesgs');
	}
}

$page->begin();

// Adjust query
$where = '1=1';

if ($search['log_action']) $where .= natural_search('t.action', $search['log_action']);
if ($search['mod_name']) $where .= natural_search('t.module_name', $search['mod_name']);
if ($search['mod_id']) $where .= natural_search('t.module_id', $search['mod_id']);
if ($search['obj_id']) $where .= natural_search('t.object_id', $search['obj_id']);
if ($search['obj_element']) $where .= natural_search('t.object_element', $search['obj_element']);
if ($search['ds']) $where .= " AND date(t.datec) >= date('".$log->db->idate($search['ds'])."')";
if ($search['de']) $where .= " AND date(t.datec) <= date('".$log->db->idate($search['de'])."')";
if ($search['user'] > 0) $where .= natural_search('t.fk_user', $search['user']);

// Fetch
$qb = new QueryBuilder();
$qb->select($log->fetch_fields, true, 't')
   ->from($log->table_element, 't')
   ->where($where)
   ->orderBy($sortfield, $sortorder);

// Get total & result count
$total = $qb->count();
$qb->limit($limit+1, $offset)->execute();
$count = $qb->count();

// List fields
$list_fields = array();
$list_fields[] = array('name' => 't.action', 'label' => 'LogAction', 'search_input' => $page->form->textInput('log_action', $search['log_action']));
$list_fields[] = array('name' => 't.module_name', 'label' => 'ModuleName', 'search_input' => $page->form->textInput('mod_name', $search['mod_name']));
$list_fields[] = array('name' => 't.module_id', 'label' => 'ModuleID', 'search_input' => $page->form->textInput('mod_id', $search['mod_id']));
$list_fields[] = array('name' => 't.object_id', 'label' => 'ObjectID', 'search_input' => $page->form->textInput('obj_id', $search['obj_id']));
$list_fields[] = array('name' => 't.object_element', 'label' => 'ObjectElement', 'search_input' => $page->form->textInput('obj_element', $search['obj_element']));
$list_fields[] = array('name' => 't.datec', 'label' => 'LogDate', 'align' => 'center', 'search_input' => $page->form->dateInput('ds', $search['ds'], 0).$page->form->dateInput('de', $search['de'], 0));
$list_fields[] = array('name' => 't.fk_user', 'label' => 'LogUser', 'align' => 'center', 'search_input' => $page->form->select_dolusers($search['user'], 'user', 1, '', 0, '', '', 0, 0, 0, '', 0, '', 'maxwidth300'));

// Print list head
$purge_button = '<a class="butActionDelete" href="'.$_SERVER["PHP_SELF"].'?action=purge">'.$langs->trans("Purge").'</a>';
$page->openList('DolibaseLogs', 'title_generic.png', $list_fields, $search, $count, $total, array(), $sortfield, $sortorder, $purge_button);

$odd = true;

// Print rows
foreach ($qb->result($limit) as $row)
{
	$odd = !$odd;
	$page->openRow($odd);

	// Action
	$page->addColumn('t.action', $row->action);

	// Module name
	$page->addColumn('t.module_name', $row->module_name);

	// Module id
	$page->addColumn('t.module_id', $row->module_id);

	// Object id + link
	if (in_array($row->module_name, array('Maintenance'))) {
		$url = '/'.strtolower($row->module_name).'/'.$row->object_element.'/card.php?id='.$row->object_id;
	}
	else {
		$url = '/'.strtolower($row->module_name).'/card.php?id='.$row->object_id;
	}
	$link = '<a href="'.dol_buildpath($url, 1).'" target="_blank">'.$row->object_id.'</a>';
	$page->addColumn('t.object_id', $link);

	// Object element
	$page->addColumn('t.object_element', $row->object_element);

	// Date
	$datec = dolibase_print_date($row->datec, "day");
	$page->addColumn('t.datec', $datec, 'align="center"');

	// User
	$userstatic->fetch($row->fk_user);
	$page->addColumn('t.fk_user', $userstatic->getNomUrl(1), 'align="center"');

	$page->closeRow();
}

// close list
$page->closeList();

$page->end();
