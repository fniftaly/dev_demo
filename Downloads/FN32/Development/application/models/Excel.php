<?php
class Application_Model_Excel {
	public $error = '';
	public $data = array();
	public $filename = '';
	public $overwrite = true; 
	public $delete = true;
	public static $excelError = null;
	
	public function __construct(Array $data = array(), $filename = '', $overwrite = true, $delete = true) {
		$this->data = $data;
		$this->filename = $filename;
		$this->overwrite = $overwrite;
		$this->delete = $delete;
	}
	
	/**
	 * Creates an Excel file
	 * 
	 * @param array $data
	 * @param string $filename
	 * @param string $error
	 * @param boolean $overwrite
	 * @return array Array of filename and filepath on success or false on failure
	 */
	public function create(Array $data = array(), $filename = '', $overwrite = null) { 
		if (!$data) {
			if (!$this->data) {
				self::$excelError = 'Missing data';
				return false;
			}
			
			$data = $this->data;
		}
		
		if (!$filename) {
			if (!$this->filename) {
				self::$excelError = 'Missing save as filename';
				return false;
			}
			
			$filename = $this->filename;
		}
		
		if ($overwrite === null) {
			if ($this->overwrite === true || $this->overwrite === false) {
				$overwrite = $this->overwrite;
			} else {
				$overwrite = true;
			}
		} else {
			$overwrite = (bool) $overwrite;
		}
		
		return self::createExcelFile($data, $filename, $overwrite);
	}
	
	/**
	 * Creates, exports and deletes an excel file as needed
	 * 
	 * @param array $data
	 * @param string $filename
	 * @param boolean $delete
	 */
	public function export(Array $data = array(), $filename = '', $delete = true) {
		if (!$data) {
			if (!$this->data) {
				self::$excelError = 'Missing data';
				return false;
			}
			
			$data = $this->data;
		}
		
		if (!$filename) {
			if (!$this->filename) {
				self::$excelError = 'Missing save as filename';
				return false;
			}
			
			$filename = $this->filename;
		}
		
		if ($delete === null) {
			if ($this->delete === true || $this->$delete === false) {
				$delete = $this->$delete;
			} else {
				$delete = true;
			}
		} else {
			$delete = (bool) $delete;
		}
		
		self::exportExcelFile($data, $filename, $delete);
	}
	
	/**
	 * Creates an Excel file
	 * 
	 * @param array $data
	 * @param string $filename
	 * @param string $error
	 * @param boolean $overwrite
	 * @return array Array of filename and filepath on success or false on failure
	 */
	public static function createExcelFile(Array $data, $filename, $overwrite = true) {
		// Register the excel write stream wrapper
		Application_Model_StreamExcel::registerWrapper('xlsfile');
		
		// Set us up a filename to create/send
		$file_name = "$filename.xls";
		
		// Get the path to that file
		//$file_path = "/tmp/$file_name";
        $file_path = "/home/textm/textmunication.com/htdocs/public/reportdocs/$file_name";
		
		// If there is no file already, create it first
		if (!file_exists($file_path) || $overwrite) { 
			// Set up the stream path - NOTE $real_path begins with a '/'
			$export_file = "xlsfile:/$file_path"; 
			
			// Open the stream for writing in binary mode
			if ( ($fp = fopen($export_file, "wb")) === false ) { 
				$error = "Cannot open $file_path for writing.";
			} else { 
				// Write the real data
				fwrite($fp, serialize($data));
				error_reporting(1);
				// Close the file handle resource
				fclose($fp); 
				
				return array('name' => $file_name, 'path' => $file_path);
			}
		} else {
			self::$excelError = 'File exists and overwrites are off';
		}
		
		return false;
	}
	
	/**
	 * Creates, exports and deletes an excel file as needed
	 * 
	 * @param array $data
	 * @param string $filename
	 * @param boolean $delete
	 */
	public static function exportExcelFile(Array $data, $filename, $delete = true) {
		if (($file = self::createExcelFile($data, $filename)) !== false) {
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
			header('Last-Modified: ' . gmdate('D,d M YH:i:s') . ' GMT');
			header('Content-type: application/x-msdownload');
			header('Content-Disposition: attachment; filename="' . $file['name'] . '"');
			header('Content-Description: Generated Download File');
			
			readfile($file['path']);
			if ($delete) {
				unlink($file['path']);
			}
			exit;
		}
		
		die(self::$excelError);
	}
}