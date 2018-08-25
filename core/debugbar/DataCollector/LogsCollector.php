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

use DebugBar\DataCollector\MessagesCollector;
use Psr\Log\LogLevel;
use ReflectionClass;

/**
 * LogsCollector class
 */

class LogsCollector extends MessagesCollector
{
	/**
	 * @var string default logs file path
	 */
	protected $path;
	/**
	 * @var int number of lines to show
	 */
	protected $lines = 124;

	/**
	 * Constructor
	 * 
	 * @param string $path
	 * @param string $name
	 */
	public function __construct($path = null, $name = 'logs')
	{
		parent::__construct($name);

		$this->path = $path;
	}

	/**
	 *	Return collected data
	 *
	 */
	public function collect()
	{
		$path = $this->path ?: $this->getLogsFile();

		$this->getStorageLogs($path);

		return parent::collect();
	}

	/**
	 * Get the path to the logs file
	 *
	 * @return string
	 */
	public function getLogsFile()
	{
		// default dolibarr log file
		$path = DOL_DATA_ROOT . '/dolibarr.log';

		return $path;
	}

	/**
	 * Get logs
	 *
	 * @param string $path
	 *
	 * @return array
	 */
	public function getStorageLogs($path)
	{
		if (! file_exists($path)) {
			return;
		}

		// Load the latest lines
		$file = implode("", $this->tailFile($path, $this->lines));

		foreach ($this->getLogs($file) as $log) {
			$this->addMessage($log['line'], $log['level'], false);
		}
	}

	/**
	 * Get latest file lines
	 * http://tekkie.flashbit.net/php/tail-functionality-in-php
	 * @param string $file
	 * @param int $lines
	 * @return array
	 */
	protected function tailFile($file, $lines)
	{
		$handle = fopen($file, "r");
		$linecounter = $lines;
		$pos = -2;
		$beginning = false;
		$text = [];
		while ($linecounter > 0) {
			$t = " ";
			while ($t != "\n") {
				if (fseek($handle, $pos, SEEK_END) == -1) {
					$beginning = true;
					break;
				}
				$t = fgetc($handle);
				$pos--;
			}
			$linecounter--;
			if ($beginning) {
				rewind($handle);
			}
			$text[$lines - $linecounter - 1] = fgets($handle);
			if ($beginning) {
				break;
			}
		}
		fclose($handle);
		return array_reverse($text);
	}

	/**
	 * Search a string for log entries
	 *
	 * @param $file
	 * @return array
	 */
	public function getLogs($file)
	{
		$pattern = "/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}.*/";
		$log_levels = $this->getLevels();
		preg_match_all($pattern, $file, $matches);
		$log = [];
		foreach ($matches as $lines) {
			foreach ($lines as $line) {
				foreach ($log_levels as $level_key => $level) {
					if (strpos(strtolower($line), strtolower($level_key)) == 20) {
						$log[] = ['level' => $level, 'line' => $line];
					}
				}
			}
		}
		$log = array_reverse($log);
		return $log;
	}

	/**
	 * Get the log levels from psr/log.
	 *
	 * @return array
	 */
	public function getLevels()
	{
		$class = new ReflectionClass(new LogLevel());
		$levels = $class->getConstants();
		$levels['ERR'] = 'error';

		return $levels;
	}
}