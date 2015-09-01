<?php
/**
 * Excel Stream Output Handler
 * 
 * The Excel Stream Output Handler handles output streams that write Excel files.
 * It can be used to make spreadsheets for use in downloading and attaching 
 * files to outbound mail messages.
 * 
 * @author farad niftaly
 */
class Application_Model_StreamExcel {
	/**
	 * Name of this class, to be used as the wrapper
	 * 
	 * @access protected
	 * @var string
	 */
	protected static $_wrapper = 'Application_Model_StreamExcel';
	
	/**
	 * The stream pointer
	 * 
	 * @access private
	 * @var integer
	 */
	private $_position = 0;
	
	/**
	 * Default stream open mode
	 * 
	 * @access private
	 * @var string
	 */
	private $_mode = 'rb';
	
	/**
	 * The name of the stream
	 * 
	 * @access private
	 * @var string
	 */
	private $_xlsFilename = null;
	
	/**
	 * Internal stream pointer position
	 * 
	 * @access private
	 * @var integer
	 */
	private $_fp = null;
	
	/**
	 * Internal write buffer
	 * 
	 * @access private
	 * @var string
	 */
	private $_buffer = null;
	
	/**
	 * Endian mode setting
	 * 
	 * @access private
	 * @var string
	 */
	private $_endian = 'unknown';
	
	/**
	 * Binary types of the endian
	 * 
	 * @access private
	 * @var array
	 */
	private $_bin = array(
		'big' => 'v',
		'little' => 's',
		'unknown' => 's',
	);
	
	/**
	 * Registers this class as a stream wrapper 
	 * 
	 * @access public
	 * @param string $protocol The named protocol to use for pulling a stream
	 * @static 
	 */
	public static function registerWrapper($protocol) {
		$has = in_array($protocol, stream_get_wrappers());
		if (!$has) {
			if (!stream_wrapper_register($protocol, self::$_wrapper)) {
				trigger_error('Could not register the named protocol: ' . $protocol, E_USER_ERROR);
			}
		}
	}
	
	/**
	 * Detect server endian mode
	 * 
	 * @access	private
	 * @see		http://www.phpdig.net/ref/rn45re877.html
	 */
	private function _detect() {
		// A hex number that may represent 'abyz'
		$abyz = 0x6162797A;

		// Convert $abyz to a binary string containing 32 bits
		// Do the conversion the way that the system architecture wants to
		switch (pack ('L', $abyz)) {
			// Compare the value to the same value converted in a Little-Endian fashion
			case pack ('V', $abyz):
				$this->_endian = 'little';
				break;

			// Compare the value to the same value converted in a Big-Endian fashion
			case pack ('N', $abyz):
				$this->_endian = 'big';
				break;

			default:
				$this->_endian = 'unknown';
		}
	}
	
	/**
	 * Called by fopen()
	 * 
	 * Note: this method name is derived from PHP so it follows the PHP naming
	 * style as opposed to the normal naming style used in most our classes
	 * 
	 * @access public
	 * @param string $path File path
	 * @param string $mode Stream open mode
	 * @param int $options Stream options (STREAM_USE_PATH | STREAM_REPORT_ERRORS)
	 * @param string $opened_path Stream opened path
	 */
	public function stream_open($path, $mode, $options, &$openedPath) {
		$url = parse_url($path);
		$this->_xlsFilename = '/' . $url['host'] . $url['path'];
		$this->_position = 0;
		$this->_mode = $mode;
		
		// detect endian mode
		$this->_detect();

		/**
		 * @todo: test for invalid mode and trigger error if required
		 */ 

		// Open underlying resource suppressing errors for files not found (but are to be made)
		$this->_fp = @fopen($this->_xlsFilename, $this->_mode);
		
		// Do we have a stream resource?
		if (is_resource($this->_fp)) {
			// Empty the buffer
			$this->_buffer = '';
			
            if (preg_match("/^w|x/", $this->_mode)) {
				// write an Excel stream header
				$str = pack(str_repeat($this->_bin[$this->_endian], 6), 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
				fwrite($this->_fp, $str);
				$openedPath = $this->_xlsFilename;
				$this->_position = strlen($str);
			}
		}
		
		return is_resource($this->_fp);
	}

	/**
	 * Read the underlying stream resource (automatically called by fread/fgets)
	 * 
	 * @access public
	 * @param int $byteCount Number of bytes to read (in 8192 byte blocks)
	 * @return string The data built by reading the file
	 * @todo Modify this to convert an excel stream to an array
	 */
    public function stream_read($byteCount) {
		if (empty($data)) {
			$data = '';
		}
		
		if (is_resource($this->_fp) && !feof($this->_fp)) {
			$data .= fread($this->_fp, $byteCount);
			$this->_position = strlen($data);
		}
		
		return $data;
	}
	
	/**
	 * Called automatically by an fwrite() to the stream
	 * 
	 * @access public
	 * @param string $data Serialized array data string representing a tabular worksheet
	 * @return integer String length of the data passed to this method
	 */
	public function stream_write($data) {
		// buffer the data
		$this->_buffer .= $data;
		return strlen($data);
    }
    
	/**
	 * Pseudo write function to manipulate the data stream before writing it
	 * 
	 * This can be modified to suit your data array
	 * 
	 * @access private
	 * @param array $data Associative array representing a tabular worksheet
	 * @return integer Size of the data written
	 */
	private function _xlsStreamWrite($data) {
		// Default the size for return
		$size = 0;
		
		if (is_array($data) && !empty($data)) {
			$row = 0;
			foreach (array_values($data) as $dataVal) {
				if (is_array($dataVal) && !empty($dataVal)) {
					if ($row == 0) {
						// write the column headers
						foreach (array_keys($dataVal) as $col => $val) {
							// next line intentionally commented out
							// since we don't want a warning about the
							// extra bytes written
							// $size += $this->write($row, $col, $val);
							$this->_xlsWriteCell($row, $col, $val);
						}
                        
						// Increment the row counter
						$row++;
					}
					
					// This writes the data to the file
					// The bit above wrote the headers
					foreach (array_values($dataVal) as $col => $val) {
						$size += $this->_xlsWriteCell($row, $col, $val);
					}
					
					$row++;
				}
			}
		}
        
		return $size;
	}
	
	/**
	 * Excel worksheet cell insertion (single-worksheet supported only)
	 * 
	 * @access private
	 * @param int $row Worksheet row number (0...65536)
	 * @param int $col Worksheet column number (0..255)
	 * @param mixed $val Worksheet cell value to write
	 * @return integer Length of the string written
	 */
	private function _xlsWriteCell($row, $col, $val) {
		if (is_float($val) || is_int($val)) {
			// doubles, floats, integers
			$str  = pack(str_repeat($this->_bin[$this->_endian], 5), 0x203, 14, $row, $col, 0x0);
			$str .= pack('d', $val);
        } else {
        	// everything else is treated as a string
        	$l    = strlen($val);
        	$str  = pack(str_repeat($this->_bin[$this->_endian], 6), 0x204, 8 + $l, $row, $col, 0x0, $l);
        	$str .= $val;
        }
        
        // Write it to the file
        fwrite($this->_fp, $str);
        $this->_position += strlen($str);
        return strlen($str);
	}

	/**
     * Called by fclose() on the stream
     * 
     * @access public
     * @return boolean 
     */
	public function stream_close() {
		if (preg_match("/^w|x/", $this->_mode)) {
			// flush the buffer
			$bufsize = $this->_xlsStreamWrite(unserialize($this->_buffer));
			
			// ...and empty it
			$this->_buffer = null;
			
			// write the xls EOF
			$str = pack(str_repeat($this->_bin[$this->_endian], 2), 0x0A, 0x00);
			$this->_position += strlen($str);
			fwrite($this->_fp, $str);
		}
		
		// ...and close the internal stream
		return fclose($this->_fp);
	}
	
	/**
	 * Handler for End of File stream
	 * 
	 * @access public
	 * @return boolean True if end of file, false otherwise
	 */
	public function stream_eof() {
		$eof = true; 
		if (is_resource($this->_fp)) {
			$eof = feof($this->_fp);
		}
		
		return $eof;
    }
}