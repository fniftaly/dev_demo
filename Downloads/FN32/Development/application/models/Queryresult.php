<?php
/**
 * Textmunication API
 * 
 * @category TextM
 * @package TextM
 * @copyright Copyright (c) 2011, Textmunication, Inc.  All rights reserved.
 * @version $Id$
 */
/**
 * Database query result object - handles result set management
 * 
 * @category TextM  
 * @package TextM_Model
 * @copyright Copyright (c) 2011, Textmunication, Inc.  All rights reserved.
 */
class Application_Model_Queryresult {
	/**
	 * The error message array
	 * 
	 * This works in a bit of a different way than usual in that there is no 
	 * error flag for this object. However, the {@see hasError()} method will
	 * use this array to tell calling apps whether there are errors in the result
	 * object.
	 * 
	 * Errors in this array will be tied, by record set index, to their receord 
	 * sets. 
	 * 
	 * @access public
	 * @var array
	 */
	public $errors = array();
	
	/**
	 * A custom error message passed by the calling app for queries
	 * 
	 * This error message can be set on a per query basis allowing calling apps
	 * to set a custom error message for each query. This message will be found
	 * in the errors arrays as 'note'.
	 * 
	 * @access public
	 * @var string
	 */
	public $errorMessage = 'There was an error in the query execution';
	
	/**
	 * Result fetch type setting
	 * 
	 * This is accessible in the public realm and will be used to decide whether
	 * to return ASSOC, NUM or BOTH array fetch types.
	 * 
	 * @access public
	 * @var string
	 */
	public $fetchType = 'BOTH';
	
	/**
	 * The current row of the active record set
	 * 
	 * @access public
	 * @var array
	 */
	public $fields = array();
	
	/**
	 * The names of the field columns of the current result
	 * 
	 * @access public
	 * @var array
	 */
	public $fieldNames = array();
	
	/**
	 * The count of columns of the current result
	 * 
	 * @access public
	 * @var integer
	 */
	public $fieldCount = 0;
	
	/**
	 * Determinator of position for first in the record set
	 * 
	 * @access public
	 * @var boolean 
	 */
	public $isFirst = false;
	
	/**
	 * Determinator of position for last in the record set
	 * 
	 * @access public
	 * @var boolean 
	 */
	public $isLast = false;
	
	/**
	 * Replicating MySQLi_Result behavior
	 *
	 * @access public
	 * @var int
	 */
	public $num_rows = 0;
	
	/**
	 * The actual recordset for this result
	 * 
	 * @access protected
	 * @var array
	 */
	protected $_recordSet = array();
	
	/**
	 * The result set count
	 * 
	 * @access protected
	 * @var integer
	 */
	protected $_recordSetCount = 0;
	
	/**
	 * The current point in the result loop
	 * 
	 * @access protected
	 * @var integer
	 */
	protected $_recordSetPoint = 0;
	
	/**
	 * The location to start the looping of the result set
	 * 
	 * @access protected
	 * @var integer
	 */
	protected $_recordSetStart = 0;
	
	/**
	 * The resultset for this result
	 * 
	 * @access protected
	 * @var array
	 */
	protected $_resultSet = array();
	
	/**
	 * The result set count
	 * 
	 * @access protected
	 * @var integer
	 */
	protected $_resultSetCount = 0;
	
	/**
	 * The current point in the result set loop
	 * 
	 * @access protected
	 * @var integer
	 */
	protected $_resultSetPoint = 0;
	
	/**
	 * The location to start the looping of the result set
	 * 
	 * @access protected
	 * @var integer
	 */
	protected $_resultSetStart = 0;
	
	/**
	 * A record loop counter
	 * 
	 * @access protected
	 * @var integer
	 */
	protected $_staticCounter = 0;
	
	/**
	 * A result set loop counter
	 * 
	 * @access protected
	 * @var integer
	 */
	protected $_staticResultCounter = 0;
	
	/**
	 * The result set setter
	 * 
	 * @access public
	 * @param resource $rs A valid MySQLi result resource identifier
	 */
	public function setResult($rs = false) {
		// A result identifier is a must have
		if (empty($rs)) {
			return false;
		}
		
		// Get multiple result sets from the MySQL query result if we can
		$recordSet = array();
		
		while ($row = $rs->fetch_assoc()) {
			$recordSet[] = $row;
		}
		
		// Add the record sets into the result set
		$this->_resultSet[] = $recordSet;
	}
	
 	/**
	 * Magic method for allowing mapping of field names to the result set object
	 * 
	 * This allows for coding like $rs->fieldname instead of $rs->fields['fieldname']
	 * 
	 * @access protected
	 * @return string Data string mapped to the field name if found, null otherwise
	 */
	public function __get($name) {
		return array_key_exists($name, $this->fields) ? $this->fields[$name] : null;
	}
	
	/**
	 * Fetches a single result row and moves the pointer to the next row
	 *
	 * @access public
	 * @return array
	 */
	public function fetch() {
		if ($this->hasRecord()) {
			$this->nextRecord();
			return $this->fields;
		}
		
		return false;
	}
	
	/**
	 * Fetches all records in the current result set
	 * 
	 * @access public
	 * @return array
	 */
	public function fetchAll() {
		return $this->_recordSet;
	}
	
	/**
	 * Fetches a single result row as an array and moves the pointer to the next
	 * row. This is an alias for {@see fetch()}
	 *
	 * @access public
	 * @return array
	 */
	public function fetchArray() {
		return $this->fetch();
	}
	
	/**
	 * Fetches a single result row as a numerically indexed array and moves the
	 * pointer to the next row.
	 *
	 * @access public
	 * @return array
	 */
	public function fetchNum() {
		if ($row = $this->fetch()) {
			return array_values($row);
		}
		
		return false;
	}
	
	/**
	 * Fetches a single result row as both a numerically indexed AND associative
	 * indexed array and moves the pointer to the next row
	 *
	 * @access public
	 * @return array
	 */
	public function fetchBoth() {
		if ($row = $this->fetch()) {
			return array_merge(array_values($row), $row);
		}
		
		return false;
	}
	
	/**
	 * Fetches a single result row as a stdClass object and moves the pointer to
	 * the next row
	 *
	 * @access public
	 * @return array
	 */
	public function fetchObject() {
		if ($row = $this->fetch()) {
			return (object) $row;
		}
		
		return false;
	}
	
	/**
	 * Builds a table out of the entire result set collection
	 * 
	 * @access public
	 * @return string
	 */
	public function buildTable() {
		$output = '';
		
		if ($this->getRecordCount()) {
			$output .= '<table width="100%" border="1">';
			$output .= '<tr>';
			for ($i = 0; $i < $this->fieldCount; $i++) {
				$output .= "<th>{this->fieldNames[$i]}</th>";
			}
			$output .= '</tr>';
		}
		
		$on = true;
		for ($this->startRecord(); $this->hasRecord(); $this->nextRecord()) {
			$class = $on ? 'result-set-on' : 'result-set-off';
			
			$output .= '<tr>';
			for ($i = 0; $i < $this->fieldCount; $i++) {
				$row = trim($this->fields[$this->fieldNames[$i]]);
				if (!$row) { 
					$row = '0' === $row || 0 === $row ? '0' : '&nbsp;';
				}
				$output .= "<td class='$class'>$row</td>";
			}
			$output .= '</tr>';
			$on = !$on;
		}
		
		$output .= '</table>'; 
		
		return $output;
	}
	
	/**
	 * Builds a table out of the entire result collection or, if specified, a
	 * resultset
	 * 
	 * @access public
	 * @return string
	 */
	public function toTable($result = null) {
		$rs = clone $this;
		
		if (is_numeric($result)) {
			$rs->setResultPoint($result);
			$rs->setFields();
			$rs->setFieldNames();
			
			return $this->buildTable($rs);
		} else {
			$return = '';
			if ($rs->getResultCount()) {
				for ($rs->startResult(); $rs->hasResult(); $rs->nextResult()) {
					$return .= $this->buildTable($rs);
				}
			}
			
			return $return;
		}
	}
	
	/**
	 * Sets a custom error message on a per query basis
	 * 
	 * @access public
	 * @param string $msg The message to set
	 */
	public function setErrorMessage($msg) {
		$this->errorMessage = $msg;
	}
	
	/**
	 * Sets the type of result array(s) to use when fetching results
	 * 
	 * @access public
	 * @param string $type One of either ASSOC, NUM or BOTH (BOTH is default)
	 */
	public function setResultFetchType($type) {
		$this->fetchType = strtoupper($type);
	}
	
	/**
	 * Sets Ironclad_Db_Result object specific pieces of information
	 * 
	 * @access public
	 */
	public function setResultStats() {
		// Handle settings of known values
		$this->_resultSetCount = count($this->_resultSet);
		
		// Set the first recordSet
		$this->setRecord();
	}
	
	/**
	 * Sets the current recordSet based on the resultSet offset
	 * 
	 * @access public
	 * @param integer $offset The result set array pointer
	 */
	public function setRecord($offset = 0) {
		if (isset($this->_resultSet[$offset])) {
			$this->_recordSet = $this->_resultSet[$offset];
			$this->setFields();
			$this->setFieldNames();
			$this->setFieldCount();
			
			// Set the count of this record set
			$this->_recordSetCount = count($this->_recordSet);
			$this->num_rows = $this->_recordSetCount;
			
			// And now set the starting point to zero for the record set
			$this->setRecordPoint();
		}
	}
	
	/**
	 * Sets the recordSet pointer to the given offset
	 * 
	 * @access public
	 * @param integer $offset The record set array pointer offset
	 */
	public function setRecordPoint($offset = 0) {
		$offset = intval($offset);
		
		if ($offset >= 0) {
			if ($offset > $this->_recordSetCount) {
				// Max out the pointer
				$this->_recordSetPoint = $this->_recordSetCount-1;
			} else {
				$this->_recordSetPoint = $offset;
			}
		} else {
			$this->_recordSetPoint = $this->_recordSetCount + $offset;
		}
	}
	
	/**
	 * Sets the resultSet pointer to the given offset
	 * 
	 * @access public
	 * @param integer $offset The result set array pointer offset
	 */
	public function setResultPoint($offset = 0) {
		$offset = intval($offset);
		
		if ($offset >= 0) {
			if ($offset > $this->_resultSetCount) {
				// Max out the pointer
				$this->_resultSetPoint = $this->_resultSetCount-1;
			} else {
				$this->_resultSetPoint = $offset;
			}
		} else {
			$this->_resultSetPoint = $this->_resultSetCount + $offset;
		}
	}
	
	/**
	 * Sets the current field array to the current pointer in the record set
	 * 
	 * @access public
	 */
	public function setFields() {
		if (!empty($this->_recordSet[$this->_recordSetPoint])) {
			$this->fields = $this->_recordSet[$this->_recordSetPoint];
		}
	}
	
	/**
	 * Sets the fieldNames property
	 * 
	 * This is useful for finding out the field names of the result set you are on.
	 * 
	 * @access public
	 */
	public function setFieldNames() {
	//	if ($this->fields) {
			$this->fieldNames = array_keys($this->fields);
	//	}
	}
	
	/**
	 * Sets the fieldCount property
	 * 
	 * This is useful for finding out the number of fields of the result set you are on.
	 * 
	 * @access public
	 */
	public function setFieldCount() {
		$this->fieldCount = count($this->fieldNames);
	}
	
	/**
	 * Gets the current result set pointer
	 * 
	 * Gets the current result set counter point
	 * 
	 * @access public
	 * @return boolean
	 */
	public function getResultSetPoint() {
		return $this->_resultSetPoint;
	}
	
	/**
	 * Gets a result set
	 * 
	 * Gets the result for the most current query, or a given index is presented
	 * 
	 * @access public
	 * @param integer $index Index of the result set array to get
	 * @return array the array of fetched rows from the query result
	 */
	public function getResults($index = null) {
		// If an index was given try to find it to return it
		if (! is_null($index)) {
			if (isset($this->_resultSet[$index])) {
				return $this->_resultSet[$index];
			}
		}
		
		// If the index is null or not found, send back the entire thing
		return $this->_resultSet;
	}
	
	/**
	 * Gets a record set
	 * 
	 * Gets the current record set for the most current query
	 * 
	 * @access public
	 * @return array the array of fetched rows from the query result
	 */
	public function getRecords() {
		return $this->_recordSet;
	}
	
	/**
	 * The result loop starting point
	 * 
	 * @access public
	 * @param integer $offset Where to start looping result sets
	 * @return integer The integer value of the loop starting point
	 */
	public function startResult($offset = 0) {
		$this->_resultSetPoint = $offset ? $offset : $this->_resultSetStart;
		return $this->_resultSetStart;
	}
	
	/**
	 * The record loop starting point
	 * 
	 * @access public
	 * @param integer $offset User supplied starting point
	 * @return integer The integer value of the loop starting point
	 */
	public function startRecord($offset = 0) {
		// This sets the record set pointer
		$this->setRecordPoint($offset);

		// This sets the current fields array
		$this->setFields();

		// Reset the counter internally
		
		$this->resetCounter();

		// Send back the starting point of the _recordSet
		return $this->_recordSetStart;
	}
	
	/**
	 * COnvenience method that resets the recordset pointer when using while 
	 * loops for assignment from fetch*() methods.
	 * 
	 * @access public
	 * @return integer Record Set starting pointer
	 */
	public function reset() {
		return $this->startRecord();
	}
	
	/**
	 * The result loop current point checker
	 * 
	 * @access public
	 * @param integer $limit Where to limit loop iterations
	 * @return boolean True if there is a current result set, false otherwise
	 */
	public function hasResult($limit = 0) {
		$loop = $limit === 0 ? $this->_resultSetCount : $limit;
		
		if ($this->getResultCounter() < $loop) {
			$this->incrementResultCounter();
			
			if (isset($this->_resultSet[$this->_resultSetPoint])) {
				/**
				 * This fixed a bug as of Rev 298 that was not moving the result 
				 * set pointer properly. This was causing inner record loops to 
				 * fail after the first iteration of records.
				 */
				$this->setRecord($this->_resultSetPoint);
				return true;
			}
		}
		
		$this->resetResultCounter();
		return false;
	}
	
	/**
	 * Identifies whether there are results
	 * 
	 * This method will scan all record sets and if just one record set has
	 * data, it will return true.
	 * 
	 * @access public
	 * @return Boolean True if there is a result set count, false otherwise
	 */
	public function hasResults() {
		// Loop through the result sets to see if any of them actually have data
		for ($i = 0; $i < $this->_resultSetCount; $i++) {
			// As soon as you find data, return true
			if (is_array($this->_resultSet[$i]) && count($this->_resultSet[$i])) {
				return true;
			}
		}
		// If we've made it here, there was no data in the result sets
		return false;
	}
	
	/**
	 * Identifies whether there is a collection of record sets
	 * 
	 * @access public
	 * @return Boolean True if there is are record sets, false otherwise
	 */
	public function hasRecordSets() {
		return $this->_resultSetCount !== 0;
	}
	
	/**
	 * The record loop current point checker
	 * 
	 * @access public
	 * @param integer $limit The loop limit to keep the recordset return to
	 * @return boolean True if there is a current record, false otherwise
	 */
	public function hasRecord($limit = 0) {
		// Set the loop max limit
		$loop = $limit === 0 ? $this->_recordSetCount : $limit;
		
		// Set our first record flag
		$this->isLast = $this->_recordSetPoint == $loop - 1;
		$this->isFirst = $this->_recordSetPoint == $this->_recordSetStart;
				
		// Only return if there are values in the record set
		// And if the counter is less than the loop
		if ($this->getCounter() < $loop) {
			$this->incrementCounter();
			if (isset($this->_recordSet[$this->_recordSetPoint]))	{
				// Set the current field into the fields array
				//$this->fields = $this->_recordSet[$this->_recordSetPoint];
				$this->setFields();
				return true;
			}
		}
		
		// Clear the current field array since there are no results at this point
		$this->fields = array();
		return false;
	}
	
	/**
	 * Identifies whether there are records in the given result set
	 * Will check the current _resultSetPoint if param is left null
	 * 
	 * @access public
	 * @param integer $offset Recordset to check
	 * @return Boolean True if there is a record set count, false otherwise
	 */
	public function hasRecords($offset = NULL) {
		// Changed the default offset to NULL so we can use the function to see if the 
		// current _resultSetPoint has records when looping 08/25/08
		if ($offset === NULL) {
			$offset = $this->_resultSetPoint;
		}
		// Check to make sure the offset passed is set and a number
		if (is_numeric($offset) && isset($this->_resultSet[$offset])) {
			return count($this->_resultSet[$offset]) !== 0;
		} 
		
		return false;
	}
	
	/**
	 * Records the current loop position for hasResult data returns
	 * 
	 * @access private
	 * @return integer The current integer value of the loop counter for result sets
	 */
	private function getResultCounter() {
		return $this->_staticResultCounter;
	}
	
	/**
	 * Increments the loop counter for results sets
	 * 
	 * @access private
	 */
	private function incrementResultCounter() {
		$this->_staticResultCounter++;
	}
	
	/**
	 * Sets the loop counter to some value for result set looping
	 * 
	 * @access private
	 */
	private function setResultCounter($value = 0) {
		if (is_integer($value)) {
			$this->_staticResultCounter = $value;
		}
	}
	
	/**
	 * Resets the current loop position for hasResult data returns to 0
	 * 
	 * @access private
	 */
	private function resetResultCounter() {
		$this->_staticResultCounter = 0;
	}
	
	/**
	 * Records the current loop position for hasRecord data returns
	 * 
	 * @access private
	 * @return integer The current integer value of the loop counter
	 */
	private function getCounter() {
		return $this->_staticCounter;
	}
	
	/**
	 * Increments the loop counter
	 * 
	 * @access private
	 */
	private function incrementCounter() {
		$this->_staticCounter++;
	}
	
	/**
	 * Sets the loop counter to some value
	 * 
	 * @access private
	 */
	private function setCounter($value = 0) {
		if (is_integer($value)) {
			$this->_staticCounter = $value;
		}
	}
	
	/**
	 * Resets the current loop position for hasRecord data returns to 0
	 * 
	 * @access private
	 */
	private function resetCounter() {
		$this->_staticCounter = 0;
	}
	
	/**
	 * The result loop movement method
	 * 
	 * @access public
	 * @return integer Increments the result loop pointer
	 */
	public function nextResult() {
		++$this->_resultSetPoint;
		$this->setRecord($this->_resultSetPoint);
        $this->resetCounter();
		return $this->_resultSetPoint;
	}
	
	/**
	 * The record loop movement method
	 * 
	 * @access public
	 * @return integer Increments the record loop pointer
	 */
	public function nextRecord() {
		return ++$this->_recordSetPoint;
	}
	
	/**
	 * The record loop current field set
	 * 
	 * This is an alias for ::fields()
	 * 
	 * @access public
	 * @return mixed Value of the current field at the current loop point, false on failure
	 */
	public function getRecord($field) {
		return $this->fields($field);
	}
	
	/**
	 * The record set point getter
	 * 
	 * Gets the current record set counter point
	 * 
	 * @access public
	 * @return boolean
	 */
	public function getRecordSetPoint() {
		return $this->_recordSetPoint;
	}
	
	/**
	 * The record loop current field set
	 * 
	 * @access public
	 * @return mixed Value of the current field at the current loop point, false on failure
	 */
	public function fields($field) {
		if (isset($this->_recordSet[$this->_recordSetPoint][$field])) {
			return $this->_recordSet[$this->_recordSetPoint][$field];
		}
		
		return false;
	}
	
	/**
	 * The result set count
	 * 
	 * @access public
	 * @return integer The result set record count of the most current query
	 */
	public function getResultCount() {
		return $this->_resultSetCount;
	}
	
	/**
	 * The current record set count
	 * 
	 * @access public
	 * @return integer The result set record count of the most current query
	 */
	public function getRecordCount() {
		return $this->_recordSetCount;
	}
	
	/**
	 * Sorts a recordset on a field in the set
	 * 
	 * @access public
	 * @param string $field The field name to sort on
	 * @param integer $sortType The PHP Constant for sort type (SORT_ASC, SORT_DESC)
	 */
	public function sortRecordSetByField($field, $sortType = SORT_ASC) {
		// Read the record set into an array for manipulation
		$array = $this->_recordSet;
		
		// Verify the field name is not empty
		// If it is simply return to the caller
		if (empty($field)) {
			return;
		}
		
		// Make sure we have a sort param
		// If not, return to the caller
		if (empty($sortType) || !is_numeric($sortType)) {
			return;
		}
		
		// Create a temporary array to sort by
		$tempArray = array();
		
		// Sort the current array holding the recordset
		foreach ($array as $key => $row) {
			$tempArray[$key] = $row[$field];
		}
		
		// Sort our array
		array_multisort($tempArray, $sortType, $array);
		
		// Reset the current recordset
		$this->_recordSet = $array;
	}
	
	/**
	 * Merges one result set into another result set. You can map fields between
	 * each result set to match eachother or 
	 * 
	 * @access public
	 * @param string $rs1 First result set
	 * @param string $rs2 Second result set that will be merged in to the first
	 * @param array $map key=>value pairs that will match the fields rs1=>rs2
	 * @return array Returns the merged result set
	 */
	public function mergeResultSets($rs1, $rs2, $map) {
		// add each record in rs2 to rs1
		for ($rs2->startRecord(); $rs2->hasRecord(); $rs2->nextRecord()) {
			// for each field in rs1, add in the mapped field value from rs2
			foreach ($rs1->fieldNames as $field) {
				$rs1->_recordSet[$rs1->_recordSetCount][$field] = $rs2->_recordSet[$rs2->_recordSetPoint][$map[$field]];
			}
			// increment the record counter
			$rs1->_recordSetCount++;
		}
		
		return $rs1;
	}
	
	/**
	 * Add a field to the fields array for a result object, then add values to it
	 * for each record.
	 * 
	 * @access public
	 * @param string $name The field name you want to add
	 * @param string $value The field value you want to set
	 */
	public function addField($name, $value) {
		if (!in_array($name, $this->fieldNames)) {
			array_push($this->fieldNames, $name);
			$this->fieldCount++;
		}
		
		if (!array_key_exists($name, $this->_recordSet[$this->_recordSetPoint])) {
			$this->_recordSet[$this->_recordSetPoint][$name] = $value;
		}
	}
	
	/**
	 * Change the value of a record set field.
	 * 
	 * @access public
	 * @param string $name The field name you want to change the value of
	 * @param string $value The new value you want the field to be
	 */
	public function changeField($name, $value) {
		if (array_key_exists($name, $this->_recordSet[$this->_recordSetPoint])) {
			$this->_recordSet[$this->_recordSetPoint][$name] = $value;
		}
	}
	
	/**
	 * Add a record to an existing record set
	 * 
	 * @access public
	 * @param array $values An array of field values that match up to the record fields
	 */
	public function addRecord($values) {
		// loop over each field name in the rs so that we are only adding fields that already exist to the new record set
		foreach ($this->fieldNames as $field) {
			// make sure the field gets set. If no field was passed in the array, set it as null in the new record
			$this->_recordSet[$this->_recordSetCount][$field] = isset($values[$field]) ? $values[$field] : null;
		}
		
		// increment the record counter
		$this->_recordSetCount++;
	}
}