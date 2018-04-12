<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('./lib/phpExcel/PHPExcel.php');
require_once('./lib/phpExcel/PHPExcel/Writer/Excel5.php');

/**
 * phpExcelWrapper library
 * Itirra - http://itirra.com
 */
class phpExcelWrapper {

	/** $xls document object. */
	protected $xls;

	/** $activeSheet object. */
	protected $activeSheet;

	/** $fontStyle. */
	public $fontStyle = array('name' => 'Calibri', 'size' => 11);

	/** $fontBoldStyle */
	public $fontBoldStyle = array('name' => 'Calibri', 'size' => 11, 'bold' => true);

	/** $borderStyle */
	public $borderStyle = array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM,
			'color' => array('rgb' => '000000')));

	/**
	 * Constructor.
	 */
	public function phpExcelWrapper () {
		$this->xls = new PHPExcel();
		$this->xls->setActiveSheetIndex(0);
		 
		$this->activeSheet = $this->xls->getActiveSheet();
		$this->activeSheet->setTitle('Sheet');
		$this->activeSheet->getDefaultStyle()->getFont()->applyFromArray($this->fontStyle);
		$this->activeSheet->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	}

	/**
	 * Get Sheet
	 * @return PHPExcel_Worksheet
	 */
	public function getSheet() {
		return $this->activeSheet;
	}

	/**
	 * Flush file to output
	 * @param string $fileName
	 */
	public function flush($fileName = 'excel_file.xls') {
		// Send HTTP-headers
		header ( "Expires: Mon, 1 Apr 1974 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
		header ( "Cache-Control: no-cache, must-revalidate" );
		header ( "Pragma: no-cache" );
		header ( "Content-type: application/vnd.ms-excel" );
		header ( "Content-Disposition: attachment; filename=" . $fileName );
		// Throw file to output
		$objWriter = new PHPExcel_Writer_Excel5($this->xls);
		$objWriter->save('php://output');
	}

}
?>