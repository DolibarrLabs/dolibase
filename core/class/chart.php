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

require_once DOL_DOCUMENT_ROOT . '/core/class/dolgraph.class.php';

/**
 * Chart class
 *
 * @since 2.9.5
 */

class Chart extends DolGraph
{
	/**
	 * Generate chart
	 * 
	 * @param     $type     chart type: 'pie', 'bars' or 'lines'
	 * @param     $data     chart data (array)
	 * @param     $legend   chart legend (array)
	 * @param     $title    chart title
	 * @param     $width    chart width
	 * @param     $height   chart height
	 * @return    $this
	 */
	public function generate($type, $data, $legend = array(), $title = '', $width = '', $height = '')
	{
		if (! $this->isGraphKo())
		{
			global $langs;

			// Fill default parameters
			if (empty($width)) {
				$width = self::getDefaultGraphSizeForStats('width');
			}
			if (empty($height)) {
				$height = self::getDefaultGraphSizeForStats('height');
			}
			$show_legend = empty($legend) ? 0 : 1;

			// Set chart settings
			$this->SetData($data);
			$this->SetLegend($legend);
			$this->setShowLegend($show_legend);
			$this->setWidth($width);
			$this->setHeight($height);
			$this->SetType(array($type));
			if (in_array($type, array('bars', 'lines'))) {
				$this->SetMaxValue($this->GetCeilMaxValue());
				$this->SetMinValue(min(0, $this->GetFloorMinValue()));
			}
			if (! empty($title)) {
				$this->SetTitle($langs->trans($title));
			}
		}

		return $this;
	}

	/**
	 * Display chart (shortcut for draw & show)
	 * 
	 * @param     $filename     chart file name
	 * @param     $fileurl      chart file url
	 * @return    $this
	 */
	public function display($filename, $fileurl = '')
	{
		if (! $this->isGraphKo())
		{
			$this->draw($filename, $fileurl);
			echo $this->show();
		}

		return $this;
	}
}
