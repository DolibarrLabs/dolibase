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
 * @copyright   Copyright (c) 2018 - 2019, AXeL-dev
 * @license     MIT
 * @link        https://github.com/AXeL-dev/dolibase
 * 
 */

/**
 * Send mail
 *
 * @param     $object     Object
 */
function send_mail($object = null)
{
	global $action, $mysoc, $db, $conf, $user, $langs;

	if (! is_object($object)) {
		dolibase_include_once('/core/class/mail_object.php');
		$object = new MailObject($db);
	}

	$id             = $object->id;
	$action         = 'send';
	$actiontypecode = '';
	$trigger_name   = '';
	$paramname      = 'id';
	$mode           = '';

	if (! isset($_POST['receivercc'])) {
		$_POST['receivercc'] = '-1'; // fix bug on dolibarr 3.9
	}

	$unset_fk_thirdparty = false;
	if (empty($object->socid) && empty($object->fk_soc) && empty($object->fk_thirdparty)) {
		$object->fk_thirdparty = 1; // fix 'ErrorFailedToReadObject' error message
		$unset_fk_thirdparty   = true;
	}

	include DOL_DOCUMENT_ROOT.'/core/actions_sendmails.inc.php';

	if ($unset_fk_thirdparty) {
		unset($object->fk_thirdparty);
	}
}

/**
 * Create mail form
 *
 * @param     $trackid                     Mail track id, could be something like: 'invoice1', 'myobject'.$id, ...
 * @param     $subject                     Mail subject
 * @param     $template                    Mail template
 * @param     $substitutions               Mail template substitutions
 * @param     $files                       Mail attachments/files
 * @param     $enable_delivery_receipt     Enable mail delivery receipt by default?
 * @param     $receivers                   Mail receivers array
 * @param     $params                      Mail additional parameters array
 * @return    string                       Mail html form
 */
function get_mail_form($trackid, $subject, $template, $substitutions = array(), $files = array(), $enable_delivery_receipt = false, $receivers = array(), $params = array())
{
	global $db, $conf, $langs, $user;

	// Create mail form
	require_once DOL_DOCUMENT_ROOT . '/core/class/html.formmail.class.php';
	$formmail = new FormMail($db);
	$formmail->param['langsmodels'] = (empty($newlang)?$langs->defaultlang:$newlang);
	$formmail->fromtype = (GETPOST('fromtype')?GETPOST('fromtype'):(!empty($conf->global->MAIN_MAIL_DEFAULT_FROMTYPE)?$conf->global->MAIN_MAIL_DEFAULT_FROMTYPE:'user'));
	if ($formmail->fromtype == 'user') {
		$formmail->fromid = $user->id;
		$formmail->frommail = $user->email; // fix for dolibarr 3.9
	}
	$formmail->trackid = $trackid;
	if (! empty($conf->global->MAIN_EMAIL_ADD_TRACK_ID) && ($conf->global->MAIN_EMAIL_ADD_TRACK_ID & 2)) { // If bit 2 is set
		require_once DOL_DOCUMENT_ROOT.'/core/lib/functions2.lib.php';
		$formmail->frommail = dolAddEmailTrackId($formmail->frommail, $formmail->trackid);
	}
	$formmail->withfrom            = 1;
	$formmail->withto              = GETPOST('sendto') ? GETPOST('sendto') : $receivers;
	$formmail->withtocc            = $receivers;
	$formmail->withtoccc           = $conf->global->MAIN_EMAIL_USECCC;
	$formmail->withtopic           = $subject;
	$formmail->withfile            = 2;
	$formmail->withbody            = $template;
	$formmail->withdeliveryreceipt = 1;
	$formmail->withcancel          = 1;
	$formmail->substit             = $substitutions;

	// Enable mail delivery receipt
	if ($enable_delivery_receipt && ! isset($_POST['deliveryreceipt'])) {
		$_POST['deliveryreceipt'] = 1;
	}

	// Array of additional parameters
	$formmail->param['action']    = 'send';
	$formmail->param['models']    = 'body';
	$formmail->param['models_id'] = GETPOST('modelmailselected', 'int');
	$formmail->param['returnurl'] = $_SERVER['PHP_SELF'];
	$formmail->param['fileinit']  = $files;
	foreach ($params as $key => $value) {
		$formmail->param[$key] = $value;
	}

	// Init list of files (for dolibarr <= 5.0)
	if (GETPOST('mode') == 'init') {
		$formmail->clear_attached_files();
		foreach ($files as $file) {
			$formmail->add_attached_files($file, basename($file), dol_mimetype($file));
		}
	}

	// Return form
	return $formmail->get_form();
}
