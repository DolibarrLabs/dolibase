<?php

// Load Dolibase DocModel class
dolibase_include_once('/core/class/doc_model.php');

// Load Dolibarr pdf lib
include_once DOL_DOCUMENT_ROOT.'/core/lib/pdf.lib.php';

/**
 * ${model_classname} class
 *
 * Class to generate PDF with template ${model_name}
 */

class ${model_classname} extends DocModel
{
	public $db;
	public $name;
	public $description;
	public $version;
	public $type;
	public $page_largeur;
	public $page_hauteur;
	public $format;
	public $marge_gauche;
	public $marge_droite;
	public $marge_haute;
	public $marge_basse;
	protected $modulepart;

	/**
	 * Constructor
	 *
	 * @param      DoliDB      $db      Database handler
	 */
	public function __construct($db)
	{
		global $langs;

		$this->db          = $db;
		$this->name        = '${model_name}';
		$this->description = $langs->trans('${model_description}');
		$this->version     = '${model_version}';
		$this->modulepart  = get_modulepart();

		// Page dimensions for A4 format
		$this->type         = 'pdf';
		$formatarray        = pdf_getFormat();
		$this->page_largeur = $formatarray['width'];
		$this->page_hauteur = $formatarray['height'];
		$this->format       = array($this->page_largeur,$this->page_hauteur);
		$this->marge_gauche = isset($conf->global->MAIN_PDF_MARGIN_LEFT)?$conf->global->MAIN_PDF_MARGIN_LEFT:10;
		$this->marge_droite = isset($conf->global->MAIN_PDF_MARGIN_RIGHT)?$conf->global->MAIN_PDF_MARGIN_RIGHT:10;
		$this->marge_haute  = isset($conf->global->MAIN_PDF_MARGIN_TOP)?$conf->global->MAIN_PDF_MARGIN_TOP:10;
		$this->marge_basse  = isset($conf->global->MAIN_PDF_MARGIN_BOTTOM)?$conf->global->MAIN_PDF_MARGIN_BOTTOM:10;
	}

	/**
	 * Function to write pdf content
	 *
	 * @param       TCPDF       $pdf                PDF object
	 * @param       Object      $object             Object to generate
	 * @param       Translate   $outputlangs        Lang output object
	 * @param       int         $default_font_size  Default font size
	 */
	protected function write_content(&$pdf, $object, $outputlangs, $default_font_size)
	{
		$width = 100;
		$height = 20;
		$x = $this->marge_gauche;
		$y = $this->marge_haute;

		$pdf->writeHTMLCell($width, $height, $x, $y, $outputlangs->convToOutputCharset('Add some content here..'), 0, 1, false, true, 'J', true);
	}

	/**
	 * Function to build pdf onto disk
	 *
	 * @param       Object      $object             Object to generate
	 * @param       Translate   $outputlangs        Lang output object
	 * @param       string      $srctemplatepath    Full path of source filename for generator using a template file
	 * @param       int         $hidedetails        Do not show line details
	 * @param       int         $hidedesc           Do not show desc
	 * @param       int         $hideref            Do not show ref
	 * @return      int                             1=OK, 0=KO
	 */
	public function write_file($object, $outputlangs, $srctemplatepath='', $hidedetails=0, $hidedesc=0, $hideref=0)
	{
		global $user, $langs, $conf, $db, $hookmanager;

		if (! is_object($outputlangs)) $outputlangs = $langs;
		// For backward compatibility with FPDF, force output charset to ISO, because FPDF expect text to be encoded in ISO
		if (! empty($conf->global->MAIN_USE_FPDF)) $outputlangs->charset_output = 'ISO-8859-1';

		$outputlangs->load("main");
		$outputlangs->load("dict");
		$outputlangs->load("companies");

		if ($conf->{$this->modulepart}->dir_output)
		{
			$object->fetch_thirdparty();

			// Definition of $dir and $file
			if ($object->specimen)
			{
				$dir = $conf->{$this->modulepart}->dir_output;
				$file = $dir . "/SPECIMEN.pdf";
			}
			else
			{
				$objectref = dol_sanitizeFileName($object->ref);
				$dir = $conf->{$this->modulepart}->dir_output . "/" . $objectref;
				$file = $dir . "/" . $objectref . ".pdf";
			}

			if (! file_exists($dir))
			{
				if (dol_mkdir($dir) < 0)
				{
					$this->error = $langs->transnoentities("ErrorCanNotCreateDir", $dir);
					return 0;
				}
			}

			if (file_exists($dir))
			{
				// Add pdfgeneration hook
				if (! is_object($hookmanager))
				{
					include_once DOL_DOCUMENT_ROOT.'/core/class/hookmanager.class.php';
					$hookmanager = new HookManager($this->db);
				}
				$hookmanager->initHooks(array('pdfgeneration'));
				$parameters = array('file' => $file, 'object' => $object, 'outputlangs' => $outputlangs);
				global $action;
				$reshook = $hookmanager->executeHooks('beforePDFCreation', $parameters, $object, $action); // Note that $action and $object may have been modified by some hooks

				// Create pdf instance
				$pdf = pdf_getInstance($this->format);
				$default_font_size = pdf_getPDFFontSize($outputlangs); // Must be after pdf_getInstance
				$pdf->SetAutoPageBreak(1, 0);

				if (class_exists('TCPDF'))
				{
					$pdf->setPrintHeader(false);
					$pdf->setPrintFooter(false);
				}

				$pdf->SetFont(pdf_getPDFFont($outputlangs));
				// Set path to the background PDF File
				if (empty($conf->global->MAIN_DISABLE_FPDI) && ! empty($conf->global->MAIN_ADD_PDF_BACKGROUND))
				{
					$pagecount = $pdf->setSourceFile($conf->mycompany->dir_output.'/'.$conf->global->MAIN_ADD_PDF_BACKGROUND);
					$tplidx = $pdf->importPage(1);
				}

				$pdf->Open();
				$pagenb = 0;
				$pdf->SetDrawColor(128, 128, 128);

				$pdf->SetTitle($outputlangs->convToOutputCharset($object->ref));
				$pdf->SetSubject($outputlangs->transnoentities($object->doc_title));
				$pdf->SetCreator("Dolibarr ".DOL_VERSION);
				$pdf->SetAuthor($outputlangs->convToOutputCharset($user->getFullName($outputlangs)));
				$pdf->SetKeyWords($outputlangs->convToOutputCharset($object->ref)." ".$outputlangs->transnoentities($object->doc_title)." ".$outputlangs->convToOutputCharset($object->thirdparty->name));
				if (! empty($conf->global->MAIN_DISABLE_PDF_COMPRESSION)) $pdf->SetCompression(false);

				$pdf->SetMargins($this->marge_gauche, $this->marge_haute, $this->marge_droite); // Left, Top, Right

				// New page
				$pdf->AddPage();
				if (! empty($tplidx)) $pdf->useTemplate($tplidx);
				$pagenb++;
				$pdf->SetFont('', '', $default_font_size - 1);
				$pdf->MultiCell(0, 3, ''); // Set interline to 3
				$pdf->SetTextColor(0, 0, 0);

				// Write content
				$this->write_content($pdf, $object, $outputlangs, $default_font_size);

				if (method_exists($pdf, 'AliasNbPages')) $pdf->AliasNbPages();

				$pdf->Close();

				$pdf->Output($file, 'F');

				// Add pdfgeneration hook
				$hookmanager->initHooks(array('pdfgeneration'));
				$parameters = array('file' => $file, 'object' => $object, 'outputlangs' => $outputlangs);
				global $action;
				$reshook = $hookmanager->executeHooks('afterPDFCreation', $parameters, $this, $action); // Note that $action and $object may have been modified by some hooks

				if (! empty($conf->global->MAIN_UMASK)) {
					@chmod($file, octdec($conf->global->MAIN_UMASK));
				}

				return 1; // No errors
			}
			else
			{
				$this->error = $langs->trans("ErrorCanNotCreateDir", $dir);
				return 0;
			}
		}
		else
		{
			$this->error = $langs->trans("ErrorConstantNotDefined", "OUTPUTDIR");
			return 0;
		}
	}
}
