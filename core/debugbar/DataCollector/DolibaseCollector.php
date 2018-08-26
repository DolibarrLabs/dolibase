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
 * @copyright	Copyright (c) 2018 - 2019, AXeL-dev
 * @license
 * @link
 * 
 */

use DebugBar\DataCollector\AssetProvider;
use DebugBar\DataCollector\DataCollector;
use DebugBar\DataCollector\Renderable;
use DebugBar\DebugBarException;

/**
 * DolibaseCollector class
 */

class DolibaseCollector extends DataCollector implements Renderable, AssetProvider
{
	/**
	 *	Return collector name
	 *
	 */
	public function getName()
	{
		return 'dolibase';
	}

	/**
	 *	Return collected data
	 *
	 */
	public function collect()
	{
		return array(
			'version' => DOLIBASE_VERSION
		);
	}

	/**
	 *	Return database info as an HTML string
	 *
	 */
	protected function getDatabaseInfo()
	{
		global $conf;

		$info  = 'Host: <strong>' . $conf->db->host . '</strong><br>';
		$info .= 'Port: <strong>' . $conf->db->port . '</strong><br>';
		$info .= 'Name: <strong>' . $conf->db->name . '</strong><br>';
		$info .= 'User: <strong>' . $conf->db->user . '</strong><br>';
		$info .= 'Type: <strong>' . $conf->db->type . '</strong><br>';
		$info .= 'Prefix: <strong>' . $conf->db->prefix . '</strong><br>';
		$info .= 'Charset: <strong>' . $conf->db->character_set . '</strong>';

		return $info;
	}

	/**
	 *	Return dolibarr info as an HTML string
	 *
	 */
	protected function getDolibarrInfo()
	{
		global $conf;

		$info  = 'Dolibarr version: <strong>' . DOL_VERSION . '</strong><br>';
		$info .= 'Theme: <strong>' . $conf->theme . '</strong><br>';
		$info .= 'Locale: <strong>' . $conf->global->MAIN_LANG_DEFAULT . '</strong><br>';
		$info .= 'Currency: <strong>' . $conf->currency . '</strong><br>';
		$info .= 'Entity: <strong>' . $conf->entity . '</strong><br>';
		$info .= 'List limit: <strong>' . ($conf->liste_limit ?: $conf->global->MAIN_SIZE_LISTE_LIMIT) . '</strong><br>';
		$info .= 'Upload size: <strong>' . $conf->global->MAIN_UPLOAD_DOC . '</strong>';

		return $info;
	}

	/**
	 *	Return mail info as an HTML string
	 *
	 */
	protected function getMailInfo()
	{
		global $conf;

		$info  = 'Method: <strong>' . $conf->global->MAIN_MAIL_SENDMODE . '</strong><br>';
		$info .= 'Server: <strong>' . $conf->global->MAIN_MAIL_SMTP_SERVER . '</strong><br>';
		$info .= 'Port: <strong>' . $conf->global->MAIN_MAIL_SMTP_PORT . '</strong><br>';
		$info .= 'ID: <strong>' . $conf->global->MAIN_MAIL_SMTPS_ID . '</strong><br>';
		$info .= 'Pwd: <strong>' . $conf->global->MAIN_MAIL_SMTPS_PW . '</strong><br>';
		$info .= 'TLS/STARTTLS: <strong>' . $conf->global->MAIN_MAIL_EMAIL_TLS . '</strong> / <strong>' . $conf->global->MAIN_MAIL_EMAIL_STARTTLS . '</strong><br>';
		$info .= 'Status: <strong>' . ($conf->global->MAIN_DISABLE_ALL_MAILS ? 'disabled' : 'enabled') . '</strong>';

		return $info;
	}

	/**
	 *	Return widget settings
	 *
	 */
	public function getWidgets()
	{
		return array(
			"dolibase_version" => array(
				"icon" => "code-fork",
				"indicator" => "PhpDebugBar.DebugBar.LinkIndicator",
				"href" => DOLIBASE_LINK,
				"target" => '_blank',
				"tooltip" => "Dolibase Version",
				"map" => "dolibase.version",
				"default" => ""
			),
			"database_info" => array(
				"icon" => "database",
				"indicator" => "PhpDebugBar.DebugBar.TooltipIndicator",
				"tooltip" => array(
					"html" => $this->getDatabaseInfo(),
					"class" => "tooltip-wide"
				),
				"map" => "",
				"default" => ""
			),
			"dolibarr_info" => array(
				"icon" => "desktop",
				"indicator" => "PhpDebugBar.DebugBar.TooltipIndicator",
				"tooltip" => array(
					"html" => $this->getDolibarrInfo(),
					"class" => "tooltip-wide"
				),
				"map" => "",
				"default" => ""
			),
			"mail_info" => array(
				"icon" => "envelope",
				"indicator" => "PhpDebugBar.DebugBar.TooltipIndicator",
				"tooltip" => array(
					"html" => $this->getMailInfo(),
					"class" => "tooltip-extra-wide"
				),
				"map" => "",
				"default" => ""
			)
		);
	}

	/**
	 *	Return collector assests
	 *
	 */
	public function getAssets()
	{
		return array(
			'base_url' => dolibase_buildurl('/core/debugbar'),
			'js' => 'js/widgets.js'
		);
	}
}