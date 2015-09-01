<?php

class Application_Model_Upload {
	/**
	 * The temporary file name for the file upload
	 * 
	 * @access private
	 */
	private $tempFileName;
	
	/**
	 * The name of the file being uploaded, after any changes
	 * 
	 * @access private
	 */
	private $fileName;
	
	/**
	 * The original name of the file being uploaded
	 * 
	 * @access private
	 */
	private $originalName;
	
	/**
	 * The directory the file will be uploaded to
	 * 
	 * @access private
	 */
	private $uploadDir;
	
	/**
	 * Maximum file size allowed for uploads
	 * 
	 * @access private
	 */
	private $maxFileSize = 524288;
	
	/**
	 * Used to store the file size of the file being uploaded
	 * 
	 * @access private
	 */
	private $fileSize = 0;
	
	/**
	 * Array of valid extension types to be uploaded
	 * 
	 * @access private
	 */
	private $extArray = array();
	
	/**
	 * Store any errors that come up during upload
	 * 
	 * @access private
	 */
	private $error;
	
	/**
	 * Store any notices that come up during upload
	 * 
	 * @access private
	 */
	private $notice;
	
	/**
	 * Name of the upload input form field
	 * 
	 * @access private
	 */
	private $form_element = 'file';
	
	/**
	 * Overwrite flag that tells the uploader whether to overwrite existing files
	 * 
	 * @access private
	 * @var boolean
	 */
	private $allowOverwrite = false;
	
	/**
	 * Object constructor
	 * 
	 * @access public
	 */
	public function __construct($params = array()) {
		// set the upload input form field name if it was set
		if (isset($params['form_element'])) {
			$this->form_element = $params['form_element'];
		}
		
		if (isset($params['overwrite'])) {
			$this->setAllowOverwrite($params['overwrite']);
		}
	}
	
	/**
	 * See if there is a file to upload and validate it's filetype against the $ext_array
	 * which contains all the valid ext types.
	 * 
	 * @access private
	 * @return boolean true if the file extension type is in $ext_array, false otherwise
	 */
	private function validateExtension() {
		// make sure there is a filename to work with
		if (empty($this->fileName)) {
			$this->error = 'ERROR: You did not enter a filename.';
			return false;
		}
		else {
			// see if we have specified only certain file types as acceptable
			// if there are none specified, we will return true
			if (empty($this->extArray)) {
				return true;
			}
			else {
				// check each accepted extension type for a leading '.'
				foreach ($this->extArray as $value) {
					// make sure the extension type has a preceding ".", if not, add one
					if ($value[0] !== '.') {
						$extensions[] = '.' . strtolower($value);
					} else {
						$extensions[] = strtolower($value);
					}
				}
				
				$extension = strtolower(strrchr($this->fileName, '.'));
				// see if the file's extension is in our acceptable file type array
				if (array_search($extension, $extensions) !== false) {
					return true;
				} else {
					$this->error = 'ERROR: The filetype is not valid.';
					return false;
				}
			}
		}
	}
	
	/**
	 * Check the size of the file being uploaded to ensure it is under the max file size
	 * 
	 * @access private
	 * @return boolean true if the file size is within the allowed limit, false otherwise
	 */
	private function validateSize() {
		$tempFileName = trim($this->tempFileName);
//		if($tempFileName == NULL){
//                           $tempFileName = trim($this->fileName);
//                }
		// see if there is a temp file name to work with
		if ($tempFileName) {
			// get the file size
			$this->fileSize = filesize($tempFileName);
			// if the file size is larger than the specified max, error
			if ($this->fileSize > $this->maxFileSize) {
				$this->error = 'ERROR: The file size exceeds the max file size limit.';
				return false;
			}
			else {
				return true;
			}
		}
		else {
			$this->error = 'ERROR: No temporary file name was generated.';
			return false;
		}
	}
	
	/**
	 * See if the file name being uploaded already exists
	 * 
	 * @access private
	 * @return boolean true if the file name is already in the specified directory, false otherwise
	 */
	private function existingFile() {
		$file = rtrim($this->uploadDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . trim($this->fileName);
		
		if (file_exists($file)) {
			$this->error = 'ERROR: The filename: <b>' . $file . '</b> is already in the directory.';
			return true;
		} else {
			return false;
		}		
	}
	
	/**
	 * Checks to see if the upload directory specified exists and can be opened
	 * 
	 * @access private
	 * @return boolean true if the directory exists and can be opened, false otherwise
	 */
	private function checkUploadDir() {
		$uploadDir = trim($this->uploadDir);
		// if the upload directory exits continue
		if ($uploadDir) {
			$udLen = strlen($uploadDir);
			
			// make sure the directory is ended with a slash
			$lastSlash = substr($uploadDir, $udLen - 1,1);
			// if not, lets put one on there
			if ($lastSlash != '/') {
				$uploadDir = $uploadDir . '/';
			}
			
			// see if we can open the directory
			$handle = @opendir($uploadDir);
			
			if ($handle) {
				// if we were able to open the dir, close it and set this function to return true
				closedir($handle);
				return true;
			}
			else {
				$this->error = 'ERROR: The directory <b>' . $uploadDir . '</b> could not be opened.';
				return false;
			}
		}
		else {
			$this->error = 'ERROR: There is no upload directory specified.';
			return false;
		}
	}
	
	/**
	 * Sets the name of the upload input form element
	 * 
	 * @access public
	 * @param $form_element
	 */
	public function setFormElement($form_element) {
		$this->form_element = $form_element;
	}
	
	/**
	 * Sets the directory the files will be uploaded to
	 * 
	 * @access public
	 * @param $directory
	 */
	public function setUploadDir($directory) {
		$this->uploadDir = $directory;
	}
	
	/**
	 * Sets where the temp file name is coming from
	 * 
	 * You can set the temp file name in the uploadFile function, or by default it will
	 * look in $_FILES.	 
	 * 
	 * @access public
	 * @param $tempFieldName
	 */
	public function setTempFileName($tempFileName) {
//            echo '<pre>'; print_r($_FILES); exit;
		if ($tempFileName) {
			$this->tempFileName = trim($tempFileName);
		} elseif (isset($_FILES[$this->form_element]['tmp_name']) && !empty($_FILES[$this->form_element]['tmp_name'])) {
			$this->tempFileName = trim($_FILES[$this->form_element]['tmp_name']);
		} else {
			$this->error = 'ERROR: There was a problem setting the temp file name.';
		}
	}
	
	/**
	 * Sets where the file name is coming from
	 * 
	 * You can set the filename in the uploadFile function, or by default it will
	 * look in $_FILES	 
	 * 
	 * @access private
	 * @param $fieldName
	 */
	private function setFileName($fileName) {
		if ($fileName) {
			$this->fileName = trim(strtolower($fileName));
		} elseif (isset($_FILES[$this->form_element]['name']) && !empty($_FILES[$this->form_element]['name'])) {
			$this->fileName = trim(strtolower($_FILES[$this->form_element]['name']));
		} else {
			$this->error = 'ERROR: There was a problem setting the file name.';
		}
	}
	
	/**
	 * Set the max file size for uploads
	 * 
	 * @access public
	 * @param $max_size
	 */
	public function setMaxFileSize($maxSize) {
		$this->maxFileSize = trim($maxSize);
	}
	
	/**
	 * Sets an array with the valid extension types for the upload
	 * 
	 * @access public
	 * @param $extens
	 */
	public function setValidExt($extens) {
		foreach ($extens as $type) {
			$extArray[] = $type;
		}
		$this->extArray = $extArray;
	}
	
	/**
	 * Changes the name the file is uploaded as
	 * 
	 * @access public
	 */
	public function setNewName($name) {
		// see if there is a file extension on the new name
		if (!strstr($name, '.')) {
			// if not get the one from the original file name and attach it to the new one
			$ext = strtolower(strrchr($this->fileName, '.'));
			$name = $name . $ext;
			$this->fileName = $name;
		} else {
			$this->fileName = strtolower($name);
		}
	}
	
	/**
	 * Sets the overwrite allowance flag
	 * 
	 * This will tell the uploader whether to allow overwriting of currently 
	 * existing files
	 * 
	 * @access public
	 * @param boolean $flag True or false depending on preference
	 */
	public function setAllowOverwrite($flag) {
		$this->allowOverwrite = (bool) $flag;
	}
	
	/**
	 * Main upload file function. Runs all validation on:
	 * 	-file size
	 * 	-valid file extension
	 * 	-existing file name in directory
	 * 	-valid upload directory
	 * Then uploads the file if all parameters are good
	 * 
	 * @access public
	 * @return boolean
	 */
	public function uploadFile($newName = '', $fileName = '', $tempFileName = '') {
		// make sure the file name is set
		//echo $this->cleanFileName($fileName); die;
		$this->setFileName($this->cleanFileName($fileName)); //die($this->fileName);
		// make sure the temp file name is set
		$this->setTempFileName($this->cleanFileName($tempFileName));
//                echo 'TMPFN : '.$this->fileName."<br>";
//                echo 'TMPFN : '.$this->tempFileName;
//                exit;
		// preserve the original file name in case it's changed
		$this->originalName = $this->fileName;
		// if there is a new name passed, set the file to be uploaded as it
		if (!empty($newName)) {
			$this->setNewName($this->cleanFileName($newName));
		}
		
		// see if the upload directory exists and can be opened and,
		// check for a valid extentsion type, valid file size, and see if this file already exists
		if ($this->checkUploadDir() && $this->validateExtension() && $this->validateSize()) {
			if ( ($this->existingFile() && $this->allowOverwrite) || !$this->existingFile()) {
				// if we have made it this far, upload the file
				if (is_uploaded_file($this->tempFileName)) {
					// if the file uploaded move it to the specified directory
					if (move_uploaded_file($this->tempFileName, $this->uploadDir . $this->fileName)) {
						return true;
					}
					else {
						$this->error = 'ERROR: The file could not be put in the directory';
						return false;
					}
				}
				else {
					$this->error = 'ERROR: The file did not upload properly.';
					return false;
				}
			}
		} else {
			return false;
		}
	}
	
	/**
	 * Return the orignal file name that was uploaded
	 * 
	 * @access public
	 * @return string
	 */
	public function getOriginalName() {
		return $this->originalName;
	}
	
	/**
	 * Return the file name that the uploaded file was saved as
	 * 
	 * @access public
	 * @return string
	 */
	public function getFileName($fullpath = false) {
		$file = './' . $this->uploadDir . DIRECTORY_SEPARATOR . $this->fileName;
		//var_dump($this->existingFile());
		return $fullpath ? realpath($this->uploadDir . DIRECTORY_SEPARATOR . $this->fileName) : $this->fileName;
	}
	
	/**
	 * Output any errors that occured during the upload
	 * 
	 * @access public
	 * @return array
	 */
	public function displayError() {
		return $this->error;
	}
	
	/**
	 * Output any notices that occured during the upload
	 * 
	 * @access public
	 * @return array
	 */
	public function displayNotice() {
		return $this->notice;
	}
	
	/**
	 * Return the file size of the uploaded file in bytes
	 * 
	 * @access public
	 * @return string
	 */
	public function getFileSize() {
		return $this->fileSize;
	}
	
	/**
	 * Return the max file size for an uploaded file in bytes
	 * 
	 * @access public
	 * @return string
	 */
	public function getMaxFileSize() {
		return $this->maxFileSize;
	}
	
	/**
	 * Removes invalid characters for a file name.
	 * Sets a notice if there were characters removed.	 
	 * 
	 * @access private
	 * @return string
	 */
	private function cleanFileName($value) {
		$count = strlen($value);
		// characters that cannot be used in a file name
		$bad = array("/", "\\", '*', '|', '?', ':', '<', '>', '"');
		$value = str_replace($bad, '', $value);
		if ($count != strlen($value)) {
			$this->notice = 'NOTICE: The characters \ / ? * < > | : " cannot be used in a filename and have been removed from the uploaded file\'s name.';
		}
		
		return $value;
	}
}
?>
