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

/**
 * DolibaseDebugBar class
 */

class DolibaseDebugBar extends DebugBar
{
	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		$this->addCollector(new PhpInfoCollector());
		$this->addCollector(new MessagesCollector());
		$this->addCollector(new RequestDataCollector());
		$this->addCollector(new ConfigCollector($this->getConfig()));
		$this->addCollector(new TimeDataCollector());
		$this->addCollector(new MemoryCollector());
		$this->addCollector(new ExceptionsCollector());
	}

	/**
	 * Returns Config array
	 *
	 */
	protected function getConfig()
	{
		global $dolibase_config;

		$config = array(
			'dolibase' => array(),
			'dolibarr' => array()
		);

		$const = get_defined_constants(true);

		// Get 'dolibase' & 'dolibarr' constants
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