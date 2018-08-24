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

use DebugBar\DataCollector\DataCollector;
use DebugBar\DataCollector\Renderable;
use DebugBar\DebugBarException;

/**
 * DolibaseInfoCollector class
 */

class DolibaseInfoCollector extends DataCollector implements Renderable
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
	 *	Return widget settings
	 *
	 */
	public function getWidgets()
	{
		return array(
            "dolibase_version" => array(
                "icon" => "code-fork",
                "tooltip" => "Dolibase Version",
                "map" => "dolibase.version",
                "default" => ""
            ),
        );
	}
}