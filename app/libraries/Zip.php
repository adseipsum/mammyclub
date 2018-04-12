<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('./lib/phpExcel/PHPExcel.php');
require_once('./lib/phpExcel/PHPExcel/Writer/Excel5.php');


class Zip {

	/**
	 * @param $files
	 * @return bool
	 * @throws Exception
	 */
	public function makeZipArchive($files) {

		$zipname = date('d_m_y') .'_siteorder.zip';
		$zip = new ZipArchive;

		if ($zip->open($zipname, ZipArchive::CREATE) === TRUE) {
			foreach ($files as $file) {
				$zip->addFile($file,basename($file));
			}

			$zip->close();

			// Send a browser zip file for download
			header('Content-Type: application/zip');
			header('Content-disposition: attachment; filename=' . $zipname);
			header('Content-Length: ' . filesize($zipname));
			readfile($zipname) or die("Archive not found.");

			// Delete zip file
			unlink($zipname);
			// Delete all generated files that are added to the archive
			array_map('unlink', glob('./web/uploads/siteorder/*'));

			return true;

		} else {
			throw new Exception('Error, can\'t create a zip file!');

			return false;
		}
	}
	
}