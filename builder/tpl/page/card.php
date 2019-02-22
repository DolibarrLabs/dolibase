<?php

// Load Dolibase
require_once 'autoload.php';

// Load Dolibase Page class
dolibase_include_once('core/pages/card.php');

// Load Object class
${object_class_include}

// Create Page using Dolibase
$page = new CardPage('${page_title}', '${access_perms}', '${modify_perms}', '${delete_perms}', ${show_documents_block});

// Get parameters
$id = GETPOST('id', 'int');
$ref = GETPOST('ref', 'alpha');
$action = GETPOST('action', 'alpha');
$confirm = GETPOST('confirm', 'alpha');
$optioncss = GETPOST('optioncss', 'alpha');
$model = GETPOST('model', 'alpha');

// Init object
${object_init}

// Set fields
//$page->setFields(array(
//	new Field(...)
//));

// Fetch object
if (($id > 0 || ! empty($ref)) && $object->fetch($id, $ref))
{
	// Set actions ---

	// Edit
	if ($page->canEdit())
	{
		// Extrafields
		if ($action == 'update_extras')
		{
			$result = $page->updateExtraFields($object);
			if ($result) $action = 'edit_extras';
		}
		// Other fields
		else if (preg_match('/set_(.*)/', $action, $field) && $page->checkField($field[1]))
		{
			if (preg_match('/date_(.*)/', $field[1], $datefield)) {
				$val  = GETPOSTDATE($datefield[1], true);
				$data = array($datefield[1] => $val);
			}
			else {
				$val  = GETPOST($field[1]);
				$val  = empty($val) ? empty_to_null($val) : str_escape($val);
				$data = array($field[1] => $val);
			}

			$object->update($data);
		}
	}

	// Delete
	if ($page->canDelete())
	{
		if ($action == 'delete')
		{
			$page->askForConfirmation($_SERVER["PHP_SELF"] . '?id=' . $object->id, 'Delete', 'Confirm Delete', 'confirm_delete', $object->ref);
		}
		else if ($action == 'confirm_delete' && $confirm == 'yes')
		{
			$result = $object->delete();
			if ($result > 0) {
				dolibase_redirect('list.php');
			}
		}
	}

	// Send by mail
	if ($action == 'send')
	{
		send_mail($object);
	}

	// --- End actions

	// Add tabs
	${tabs}

	$page->begin();

	$page->openTable(array(), 'class="border" width="100%"');

	// ...

	// Extra fields
	$page->showExtraFields($object);

	$page->closeTable();

	// Action buttons
	if ($optioncss != 'print' && $action != 'presend')
	{
		// Send by mail
		$page->addButton('SendByMail', $_SERVER["PHP_SELF"] . '?id=' . $object->id . '&action=presend&mode=init');

		// Print
		$page->addButton('Print', $_SERVER["PHP_SELF"] . '?id=' . $object->id . '&optioncss=print', '_blank');

		// Delete
		if ($page->canDelete()) {
			$page->addButton('Delete', $_SERVER["PHP_SELF"] . '?id=' . $object->id . '&action=delete', '', 'butActionDelete', true);
		}
	}
}
else $page->begin()->notFound();

$page->end($object);
