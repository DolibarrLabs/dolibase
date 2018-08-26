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
 * QueryCollector class
 */

class QueryCollector extends DataCollector implements Renderable, AssetProvider
{
	/**
	 * @var object Database handler
	 */
	protected $db;

	/**
	 * Constructor
	 * 
	 * @param     $db         Database handler
	 */
	public function __construct($db)
	{
		$this->db = $db;
	}

	/**
	 *	Return collected data
	 *
	 */
	public function collect()
	{
		$queries = array();
		$totalExecTime = 0;
		$totalMemoryUsage = 0;
		$totalFailed = 0;
		foreach ($this->db->queries as $query) {
			$queries[] = array(
				'sql' => $query['sql'],
				'duration' => $query['duration'],
				'duration_str' => $this->formatDuration($query['duration']),
				'memory' => $query['memory_usage'],
				'memory_str' => $this->formatBytes($query['memory_usage']),
				'is_success' => $query['is_success'],
				'error_code' => $query['error_code'],
				'error_message' => $query['error_message']
			);
			$totalExecTime += $query['duration'];
			$totalMemoryUsage += $query['memory_usage'];
			if (! $query['is_success']) {
				$totalFailed += 1;
			}
		}

		return array(
			'nb_statements' => count($queries),
			'nb_failed_statements' => $totalFailed,
			'accumulated_duration' => $totalExecTime,
			'accumulated_duration_str' => $this->formatDuration($totalExecTime),
			'memory_usage' => $totalMemoryUsage,
			'memory_usage_str' => $this->formatBytes($totalMemoryUsage),
			'statements' => $queries
		);
	}

	/**
	 *	Return collector name
	 *
	 */
	public function getName()
	{
		return 'query';
	}

	/**
	 *	Return widget settings
	 *
	 */
	public function getWidgets()
	{
		return array(
			"database" => array(
				"icon" => "arrow-right",
				"widget" => "PhpDebugBar.Widgets.SQLQueriesWidget",
				"map" => "query",
				"default" => "[]"
			),
			"database:badge" => array(
				"map" => "query.nb_statements",
				"default" => 0
			)
		);
	}

	/**
	 *	Return assets
	 *
	 */
	public function getAssets()
	{
		return array(
			'css' => 'widgets/sqlqueries/widget.css',
			'js' => 'widgets/sqlqueries/widget.js'
		);
	}
}