<?php
class Application_Model_Deliveryreport extends Application_Model_Abstract {
	/**
	 * PROPDESCRIPTION
	 * 
	 * @access public
	 * @var PROPTYPE
	 */
	public $message_id;

	/**
	 * PROPDESCRIPTION
	 * 
	 * @access public
	 * @var PROPTYPE
	 */
	public $message_subid;

	/**
	 * PROPDESCRIPTION
	 * 
	 * @access public
	 * @var PROPTYPE
	 */
	public $carrier;

	/**
	 * PROPDESCRIPTION
	 * 
	 * @access public
	 * @var PROPTYPE
	 */
	public $channel;

	/**
	 * PROPDESCRIPTION
	 * 
	 * @access public
	 * @var PROPTYPE
	 */
	public $device_address;

	/**
	 * PROPDESCRIPTION
	 * 
	 * @access public
	 * @var PROPTYPE
	 */
	public $inbound_address;

	/**
	 * PROPDESCRIPTION
	 * 
	 * @access public
	 * @var PROPTYPE
	 */
	public $status;

	/**
	 * PROPDESCRIPTION
	 * 
	 * @access public
	 * @var PROPTYPE
	 */
	public $status_code;

	/**
	 * PROPDESCRIPTION
	 * 
	 * @access public
	 * @var PROPTYPE
	 */
	public $status_info;

	/**
	 * PROPDESCRIPTION
	 * 
	 * @access public
	 * @var PROPTYPE
	 */
	public $update_date;

	/**
	 * PROPDESCRIPTION
	 * 
	 * @access public
	 * @var PROPTYPE
	 */
	public $date_format;

	/**
	 * PROPDESCRIPTION
	 * 
	 * @access public
	 * @var PROPTYPE
	 */
	public $reportingkey1;

	/**
	 * PROPDESCRIPTION
	 * 
	 * @access public
	 * @var PROPTYPE
	 */
	public $reportingkey2;

	/**
	 * PROPDESCRIPTION
	 * 
	 * @access public
	 * @var PROPTYPE
	 */
	public $router;

	/**
	 * PROPDESCRIPTION
	 * 
	 * @access public
	 * @var PROPTYPE
	 */
	public $smscid;

	/**
	 * PROPDESCRIPTION
	 * 
	 * @access public
	 * @var PROPTYPE
	 */
	public $createtime;
	
	/**
	 * The list of properties to set as values from within this model
	 * 
	 * We can't use get_class_vars because it will pick up ALL props, including
	 * inherited props, which would be bad
	 * 
	 * @var array
	 */
	protected $_props = array(
		'message_id'      => 'message_id',
		'message_subid'   => 'message_subid', 
		'carrier'         => 'carrier', 
		'channel'         => 'channel', 
		'device_address'  => 'device_address',
		'inbound_address' => 'inbound_address', 
		'status'          => 'status', 
		'status_code'     => 'status_code', 
		'status_info'     => 'status_info', 
		'update_date'     => 'update_date',
		'date_format'     => 'date_format', 
		'reportingkey1'   => 'reportingkey1', 
		'reportingkey2'   => 'reportingkey2', 
		'router'          => 'router', 
		'smscid'          => 'smscid',
	);
	
	/**
	 * METHODDESCRIPTION
	 * 
	 * @access public
	 * @return RETURNTYPE RETURNDESCRIPTION
	 */
	public function getMessageId() {
		return $this->message_id;
	}

	/**
	 * METHODDESCRIPTION
	 * 
	 * @access public
	 * @param ARGTYPE $messageId ARGDESCRIPTION
	 */
	public function setMessageId($messageId) {
		$this->message_id = $messageId;
	}

	/**
	 * METHODDESCRIPTION
	 * 
	 * @access public
	 * @return RETURNTYPE RETURNDESCRIPTION
	 */
	public function getMessageSubid() {
		return $this->message_subid;
	}

	/**
	 * METHODDESCRIPTION
	 * 
	 * @access public
	 * @param ARGTYPE $messageSubid ARGDESCRIPTION
	 */
	public function setMessageSubid($messageSubid) {
		$this->message_subid = $messageSubid;
	}

	/**
	 * METHODDESCRIPTION
	 * 
	 * @access public
	 * @return RETURNTYPE RETURNDESCRIPTION
	 */
	public function getCarrier() {
		return $this->carrier;
	}

	/**
	 * METHODDESCRIPTION
	 * 
	 * @access public
	 * @param ARGTYPE $carrier ARGDESCRIPTION
	 */
	public function setCarrier($carrier) {
		$this->carrier = $carrier;
	}

	/**
	 * METHODDESCRIPTION
	 * 
	 * @access public
	 * @return RETURNTYPE RETURNDESCRIPTION
	 */
	public function getChannel() {
		return $this->channel;
	}

	/**
	 * METHODDESCRIPTION
	 * 
	 * @access public
	 * @param ARGTYPE $channel ARGDESCRIPTION
	 */
	public function setChannel($channel) {
		$this->channel = $channel;
	}

	/**
	 * METHODDESCRIPTION
	 * 
	 * @access public
	 * @return RETURNTYPE RETURNDESCRIPTION
	 */
	public function getDeviceAddress() {
		return $this->device_address;
	}

	/**
	 * METHODDESCRIPTION
	 * 
	 * @access public
	 * @param ARGTYPE $deviceAddress ARGDESCRIPTION
	 */
	public function setDeviceAddress($deviceAddress) {
		$this->device_address = $deviceAddress;
	}

	/**
	 * METHODDESCRIPTION
	 * 
	 * @access public
	 * @return RETURNTYPE RETURNDESCRIPTION
	 */
	public function getInboundAddress() {
		return $this->inbound_address;
	}

	/**
	 * METHODDESCRIPTION
	 * 
	 * @access public
	 * @param ARGTYPE $inboundAddress ARGDESCRIPTION
	 */
	public function setInboundAddress($inboundAddress) {
		$this->inbound_address = $inboundAddress;
	}

	/**
	 * METHODDESCRIPTION
	 * 
	 * @access public
	 * @return RETURNTYPE RETURNDESCRIPTION
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * METHODDESCRIPTION
	 * 
	 * @access public
	 * @param ARGTYPE $status ARGDESCRIPTION
	 */
	public function setStatus($status) {
		$this->status = $status;
	}

	/**
	 * METHODDESCRIPTION
	 * 
	 * @access public
	 * @return RETURNTYPE RETURNDESCRIPTION
	 */
	public function getStatusCode() {
		return $this->status_code;
	}

	/**
	 * METHODDESCRIPTION
	 * 
	 * @access public
	 * @param ARGTYPE $statusCode ARGDESCRIPTION
	 */
	public function setStatusCode($statusCode) {
		$this->status_code = $statusCode;
	}

	/**
	 * METHODDESCRIPTION
	 * 
	 * @access public
	 * @return RETURNTYPE RETURNDESCRIPTION
	 */
	public function getStatusInfo() {
		return $this->status_info;
	}

	/**
	 * METHODDESCRIPTION
	 * 
	 * @access public
	 * @param ARGTYPE $statusInfo ARGDESCRIPTION
	 */
	public function setStatusInfo($statusInfo) {
		$this->status_info = $statusInfo;
	}

	/**
	 * METHODDESCRIPTION
	 * 
	 * @access public
	 * @return RETURNTYPE RETURNDESCRIPTION
	 */
	public function getUpdateDate() {
		return $this->update_date;
	}

	/**
	 * METHODDESCRIPTION
	 * 
	 * @access public
	 * @param ARGTYPE $updateDate ARGDESCRIPTION
	 */
	public function setUpdateDate($updateDate) {
		$this->update_date = $updateDate;
	}

	/**
	 * METHODDESCRIPTION
	 * 
	 * @access public
	 * @return RETURNTYPE RETURNDESCRIPTION
	 */
	public function getDateFormat() {
		return $this->date_format;
	}

	/**
	 * METHODDESCRIPTION
	 * 
	 * @access public
	 * @param ARGTYPE $dateFormat ARGDESCRIPTION
	 */
	public function setDateFormat($dateFormat) {
		$this->date_format = $dateFormat;
	}

	/**
	 * METHODDESCRIPTION
	 * 
	 * @access public
	 * @return RETURNTYPE RETURNDESCRIPTION
	 */
	public function getReportingkey1() {
		return $this->reportingkey1;
	}

	/**
	 * METHODDESCRIPTION
	 * 
	 * @access public
	 * @param ARGTYPE $reportingkey1 ARGDESCRIPTION
	 */
	public function setReportingkey1($reportingkey1) {
		$this->reportingkey1 = $reportingkey1;
	}

	/**
	 * METHODDESCRIPTION
	 * 
	 * @access public
	 * @return RETURNTYPE RETURNDESCRIPTION
	 */
	public function getReportingkey2() {
		return $this->reportingkey2;
	}

	/**
	 * METHODDESCRIPTION
	 * 
	 * @access public
	 * @param ARGTYPE $reportingkey2 ARGDESCRIPTION
	 */
	public function setReportingkey2($reportingkey2) {
		$this->reportingkey2 = $reportingkey2;
	}

	/**
	 * METHODDESCRIPTION
	 * 
	 * @access public
	 * @return RETURNTYPE RETURNDESCRIPTION
	 */
	public function getRouter() {
		return $this->router;
	}

	/**
	 * METHODDESCRIPTION
	 * 
	 * @access public
	 * @param ARGTYPE $router ARGDESCRIPTION
	 */
	public function setRouter($router) {
		$this->router = $router;
	}

	/**
	 * METHODDESCRIPTION
	 * 
	 * @access public
	 * @return RETURNTYPE RETURNDESCRIPTION
	 */
	public function getSmscid() {
		return $this->smscid;
	}

	/**
	 * METHODDESCRIPTION
	 * 
	 * @access public
	 * @param ARGTYPE $smscid ARGDESCRIPTION
	 */
	public function setSmscid($smscid) {
		$this->smscid = $smscid;
	}

	/**
	 * METHODDESCRIPTION
	 * 
	 * @access public
	 * @return RETURNTYPE RETURNDESCRIPTION
	 */
	public function getCreatetime() {
		return $this->createtime;
	}

	/**
	 * METHODDESCRIPTION
	 * 
	 * @access public
	 * @param ARGTYPE $createtime ARGDESCRIPTION
	 */
	public function setCreatetime($createtime) {
		$this->createtime = $createtime;
	}
	
	/**
	 * Writes the status report to the database
	 * 
	 * @return boolean
	 */
	public function save() {
		$sql = "CALL message_log_status(
			'$this->message_id', '$this->message_subid', '$this->carrier', '$this->channel',
			'$this->device_address', '$this->inbound_address', '$this->status', '$this->status_code',
			'$this->status_info', '$this->update_date', '$this->date_format', '$this->reportingkey1',
			'$this->reportingkey2', '$this->router', '$this->smscid'
		)";
		
		$rs = $this->query($sql);
		
		if ($this->hasError()) {
	        error_log($this->getError());
	        $this->error = 'Unable to log inbound delivery report';
	        return false;
	    }
	    
	    return true;
	}
	
	/**
	 * Writes a delivery report to a log file for reading later
	 * 
	 * If there is no data passed, this will attempt to use the properties of this
	 * model
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function writeLog($data = array()) {
		// Get the log file name
		$logfile = $this->settings()->deliveryreportlogfile;
		if (!$logfile) {
			$logfile = 'deliveryreport.log';
		}
		
		// Build the full path to the file
		$logpath = realpath(dirname(__FILE__) . '/..') . '/logs/status';
		if (!file_exists($logpath)) {
			mkdir($logpath);
		}
		$logpath .= '/' . $logfile;
		
		// Check and/or create 
		if (!file_exists($logpath)) {
			touch($logpath);
		}
		
		if (!is_writable($logpath)) {
			chmod($logpath, 7777);
		}
		
		if (!$data) {
			foreach (get_class_vars(__CLASS__) as $n => $p) {
				if (isset($this->_props[$n])) {
					$data[$n] = $this->{$n};
				}
			}
		}
		
		// Build out line to write
		$line = json_encode($data);
		/*
		$line  = str_pad($this->message_id, 40, ' ', STR_PAD_RIGHT);
		$line .= str_pad($this->message_subid, 40, ' ', STR_PAD_RIGHT);
		$line .= str_pad($this->carrier, 40, ' ', STR_PAD_RIGHT);
		$line .= str_pad($this->channel, 40, ' ', STR_PAD_RIGHT);
		$line .= str_pad($this->device_address, 40, ' ', STR_PAD_RIGHT);
		$line .= str_pad($this->inbound_address, 40, ' ', STR_PAD_RIGHT);
		$line .= str_pad($this->status, 40, ' ', STR_PAD_RIGHT);
		$line .= str_pad($this->status_code, 40, ' ', STR_PAD_RIGHT);
		$line .= str_pad($this->status_info, 40, ' ', STR_PAD_RIGHT);
		$line .= str_pad($this->update_date, 40, ' ', STR_PAD_RIGHT);
		$line .= str_pad($this->date_format, 40, ' ', STR_PAD_RIGHT);
		$line .= str_pad($this->reportingkey1, 40, ' ', STR_PAD_RIGHT);
		$line .= str_pad($this->reportingkey2, 40, ' ', STR_PAD_RIGHT);
		$line .= str_pad($this->router, 40, ' ', STR_PAD_RIGHT);
		$line .= $this->smscid;
		*/
		// Open the file and write
		$fh = fopen($logpath, 'a');
		fwrite($fh, "$line\n");
		fclose($fh);
		
		return true;
	}
	
	/**
	 * Purges a log file, saves its data, moves it to another file and truncates it
	 * 
	 * @return boolean
	 */
	public function purgeLog() {
		// Get the log file name
		$logfile = $this->settings()->deliveryreportlogfile;
		if (!$logfile) {
			$logfile = 'deliveryreport.log';
		}
		
		// Build the full path to the file
		$logpath = realpath(dirname(__FILE__) . '/..') . '/logs/status';
		if (!file_exists($logpath)) {
			mkdir($logpath);
		}
		// This is the new file that will be used for reading from
		$newpath = $logpath.'/deliveryreport-' . date('YmdHi') . '.log';
		
		// The is the log file that will be copied to the new then truncated
		$logpath .= '/' . $logfile;
		
		// Copy now
		if (!copy($logpath, $newpath)) {
			// Use the current file for reading from
			$newpath = $logpath;
		} 
		
		// Check and/or create 
		if (is_readable($newpath)) {
			// Get our data
			$lines = file($newpath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
			
			// Kill the file
			$fh = fopen($logpath, 'w');
			fclose($fh);
			
			// Write the data, preparing a failures stack
			$failures = '';
			for ($i = 0, $l = count($lines); $i < $l; $i++) {
				$data = json_decode($lines[$i], true);
				$this->loadFromArray($data);
				if (!$this->save()) {
					// The save didn't take, send as email?
					$failures .= print_r($data, 1) . "\n";
				}
			}
			
			// if there were failures...
			if ($failures) {
				mail('wais@textmunication.com, andrew@textmunication.com', 'A delivery report save failed', $failures, 'From: alerts@textmunication.com');
			}
			
			return true;
		}
		
		return false;
	}
}

