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

dolibase_include_once('/core/class/autoloader.php');
Autoloader::register();

use \DebugBar\DebugBar;
use \DebugBar\DataCollector\PhpInfoCollector;
use \DebugBar\DataCollector\MessagesCollector;
use \DebugBar\DataCollector\RequestDataCollector;
use \DebugBar\DataCollector\ConfigCollector;
use \DebugBar\DataCollector\TimeDataCollector;
use \DebugBar\DataCollector\MemoryCollector;
use \DebugBar\DataCollector\ExceptionsCollector;
dolibase_include_once('/core/debugbar/DataCollector/QueryCollector.php');
dolibase_include_once('/core/debugbar/TraceableDB.php');
dolibase_include_once('/core/debugbar/DataCollector/DolibaseCollector.php');
dolibase_include_once('/core/debugbar/DataCollector/LogsCollector.php');

/**
 * DolibaseDebugBar class
 *
 * @see http://phpdebugbar.com/docs/base-collectors.html#base-collectors
 */

class DolibaseDebugBar extends DebugBar
{
	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		global $conf;

		//$this->addCollector(new PhpInfoCollector());
		$this->addCollector(new MessagesCollector());
		$this->addCollector(new RequestDataCollector());
		$this->addCollector(new ConfigCollector($this->getConfig()));
		$this->addCollector(new TimeDataCollector());
		$this->addCollector(new MemoryCollector());
		$this->addCollector(new ExceptionsCollector());
		$this->addCollector(new QueryCollector($this->getDatabase()));
		$this->addCollector(new DolibaseCollector());
		if ($conf->syslog->enabled) {
			$this->addCollector(new LogsCollector());
		}
	}

	/**
	 * Returns database object
	 *
	 */
	protected function getDatabase()
	{
		global $db;

		$db = new TraceableDB($db);

		return $db;
	}

	/**
	 * Returns an array with config data
	 *
	 */
	protected function getConfig()
	{
		global $dolibase_config, $dolibase_path, $dolibase_tables, $conf, $user;

		$config = array(
			'dolibase' => array(
				'const' => array(),
				'var'   => array(
					'$dolibase_path'   => $dolibase_path,
					'$dolibase_tables' => $dolibase_tables,
					'$dolibase_config' => $dolibase_config
				)
			),
			'dolibarr' => array(
				'const' => array(),
				'var'   => array(
					'$conf' => object_to_array($conf),
					'$user' => object_to_array($user)
				)
			)
		);

		// Get constants
		$const = get_defined_constants(true);

		// Separate constants
		foreach ($const['user'] as $key => $value)
		{
			if (substr($key, 0, 8) == 'DOLIBASE') {
				$config['dolibase']['const'][$key] = $value;
			}
			else  {
				$config['dolibarr']['const'][$key] = $value;
			}
		}

		return $config;
	}

	/**
	 * Returns a JavascriptRenderer for this instance
	 *
	 */
	public function getRenderer()
	{
		return parent::getJavascriptRenderer(dolibase_buildurl('/extra/DebugBar/Resources'));
	}
}