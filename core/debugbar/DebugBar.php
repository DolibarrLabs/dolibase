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
dolibase_include_once('/core/debugbar/DataCollector/DatabaseCollector.php');
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

		$this->addCollector(new PhpInfoCollector());
		$this->addCollector(new MessagesCollector());
		$this->addCollector(new RequestDataCollector());
		$this->addCollector(new ConfigCollector($this->getConfig()));
		$this->addCollector(new TimeDataCollector());
		$this->addCollector(new MemoryCollector());
		$this->addCollector(new ExceptionsCollector());
		$this->addCollector(new DatabaseCollector($this->getDatabase()));
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
		global $dolibase_config, $conf, $user;

		$config = array(
			'dolibase' => array(),
			'dolibarr' => array(),
			'$conf'    => (array) $conf,
			'$user'    => (array) $user
		);

		// Get constants
		$const = get_defined_constants(true);

		// Separate constants
		foreach ($const['user'] as $key => $value)
		{
			if (substr($key, 0, 8) == 'DOLIBASE') {
				$config['dolibase'][$key] = $value;
			}
			else  {
				$config['dolibarr'][$key] = $value;
			}
		}

		return array_merge($config, $dolibase_config);
	}

	/**
	 * Returns a JavascriptRenderer for this instance
	 *
	 */
	public function getRenderer()
	{
		return parent::getJavascriptRenderer(DOL_URL_ROOT.DOLIBASE_PATH.'/extra/DebugBar/Resources');
	}
}