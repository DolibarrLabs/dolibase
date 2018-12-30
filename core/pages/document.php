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

dolibase_include_once('core/class/page.php');
require_once DOL_DOCUMENT_ROOT . '/core/lib/files.lib.php';
require_once DOL_DOCUMENT_ROOT . '/core/class/html.formfile.class.php';

/**
 * DocumentPage class
 */

class DocumentPage extends Page
{
	/**
	 * Constructor
	 * 
	 * @param     $page_title     HTML page title
	 * @param     $access_perm    Access permission
	 */
	public function __construct($page_title, $access_perm = '')
	{
		global $langs;

		// Load lang files
		$langs->load("other");

		// Add CSS files
		$this->appendToHead('<link rel="stylesheet" type="text/css" href="'.dolibase_buildurl('core/css/banner.css.php').'">'."\n");

		parent::__construct($page_title, $access_perm);
	}

	/**
	 * Generate tabs
	 *
	 * @param     $noheader     -1 or 0=Add tab header, 1=no tab header.
	 */
	protected function generateTabs($noheader = -1)
	{
		parent::generateTabs($noheader);
	}

	/**
	 * Show banner
	 *
	 * @param     $object         object
	 * @param     $list_link      link to list
	 * @param     $morehtmlleft   more html in the left
	 */
	public function showBanner($object, $list_link = '', $morehtmlleft = '')
	{
		global $langs;

		$morehtml = (empty($list_link) ? '' : '<a href="'.dol_buildpath($list_link, 1).'">'.$langs->trans("BackToList").'</a>');

		dol_banner_tab($object, 'ref', $morehtml, 1, 'ref', 'ref', '', '', 0, $morehtmlleft);

		echo '<div class="underbanner clearboth"></div>';
	}

	/**
	 * Return Tab title
	 *
	 * @param     $object         object
	 */
	public static function getTabTitle($object)
	{
		global $conf, $db, $langs;

		require_once DOL_DOCUMENT_ROOT.'/core/class/link.class.php';

		$modulepart  = get_modulepart(); // cannot use '$this->modulepart' because this function is static
		$upload_dir  = $conf->$modulepart->dir_output . "/" . dol_sanitizeFileName($object->ref);
		$nbFiles     = count(dol_dir_list($upload_dir, 'files', 0, '', '(\.meta|_preview.*\.png)$'));
		$nbLinks     = Link::count($db, $object->element, $object->id);
		$nbDocuments = $nbFiles + $nbLinks;

		return $langs->trans('Documents') . ($nbDocuments > 0 ? ' <span class="badge">'.$nbDocuments.'</span>' : '');
	}

	/**
	 * Generate page beginning + print documents/linked files
	 *
	 * @param     $object         object
	 */
	public function begin($object = null)
	{
		if (is_object($object))
		{
			global $langs, $conf, $db, $hookmanager, $maxwidthmini, $maxheightmini;

			$upload_dir = $conf->{$this->modulepart}->dir_output.'/'.dol_sanitizeFileName($object->ref);

			// Get parameters
			$action  = GETPOST('action', 'alpha');
			$confirm = GETPOST('confirm','alpha');

			// Actions
			require_once DOL_DOCUMENT_ROOT . '/core/lib/images.lib.php'; // should be here
			require_once DOL_DOCUMENT_ROOT . '/core/actions_linkedfiles.inc.php';

			// Fix documents number after upload a file or add a link by refreshing the page
			if ((GETPOST('sendit','none') || GETPOST('linkit','none')) && ! empty($conf->global->MAIN_UPLOAD_DOC))
			{
				dolibase_redirect($_SERVER["PHP_SELF"] . '?id=' . $object->id);
			}
		}

		parent::begin();
	}

	/**
	 * Print documents/linked files
	 *
	 * @param     $object         object
	 */
	public function printDocuments($object)
	{
		if ($object->id > 0)
		{
			global $langs, $conf, $user, $db, $sortfield, $sortorder;

			$upload_dir = $conf->{$this->modulepart}->dir_output.'/'.dol_sanitizeFileName($object->ref);

			// Get parameters
			$action    = GETPOST('action', 'alpha');
			$sortfield = GETPOST('sortfield', 'alpha');
			$sortorder = GETPOST('sortorder', 'alpha');
			if (! $sortorder) $sortorder = 'ASC';
			if (! $sortfield) $sortfield = 'name';

			// Construct files list
			$filearray = dol_dir_list($upload_dir, 'files', 0, '', '(\.meta|_preview.*\.png)$', $sortfield, (strtolower($sortorder)=='desc'?SORT_DESC:SORT_ASC), 1);
			$totalsize = 0;
			foreach($filearray as $key => $file)
			{
				$totalsize += $file['size'];
			}

			echo '<div class="fichecenter">';
			echo '<table class="border" width="100%">';

			// Files infos
			echo '<tr><td class="titlefield">'.$langs->trans("NbOfAttachedFiles").'</td><td>'.count($filearray).'</td></tr>';
			echo '<tr><td>'.$langs->trans("TotalSizeOfAttachedFiles").'</td><td>'.$totalsize.' '.$langs->trans("bytes").'</td></tr>';

			echo "</table>\n";
			echo '</div>';

			$form         = new Form($db);
			$permission   = $user->rights->{$this->rights_class}->create;
			$permtoedit   = $user->rights->{$this->rights_class}->modify;
			$modulepart   = $this->modulepart;
			$param        = '';
			include_once DOL_DOCUMENT_ROOT . '/core/tpl/document_actions_post_headers.tpl.php';
		}
	}
}
