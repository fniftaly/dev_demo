<?php
/**
 * Query profiler class
 *
 * This static class acts as a container for all things query and is accessible
 * from client apps to get profile information on queries and the query stack.
 *
 */
class Application_Model_Queryprofiler {
	/**
	 * The current query
	 *
	 * @access protected
	 * @var string
	 */
	protected static $_query;
	
	/**
	 * The current query stack index
	 *
	 * @access protected
	 * @var int
	 */
	protected static $_queryId = 0;
	
	/**
	 * The stack of queries that have been executed
	 *
	 * @access protected
	 * @var array
	 */
	protected static $_queryStack = array();
	
	/**
	 * The current query execution time
	 *
	 * @access protected
	 * @var float
	 */
	protected static $_queryTime = 0;
	
	/**
	 * The total query execution time
	 *
	 * @access protected
	 * @var float
	 */
	protected static $_queryTimeTotal = 0;
	
	/**
	 * Sets a query into the profiler
	 *
	 * @access public
	 * @param string $query
	 */
	public static function setQuery($query) {
		self::$_query = $query;
		self::$_queryId++;
		self::$_queryStack[self::$_queryId]['query'] = $query;
	}
	
	public static function setQueryTime($time) {
		self::$_queryTime = $time;
		self::$_queryStack[self::$_queryId]['time'] = self::$_queryTime;
		self::$_queryTimeTotal += self::$_queryTime;
	}
	
	public static function setQueryError($error) {
		self::$_queryStack[self::$_queryId]['error'] = $error;
	}
	
	public static function getQueryCount() {
		return count(self::$_queryStack);
	}
	
	public static function getQueryId() {
		return self::$_queryId;
	}
	
	public static function getQueryStack($id = 0) {
		return $id && isset(self::$_queryStack[$id]) ? self::$_queryStack[$id] : self::$_queryStack;
	}
	
	public static function getQueryTime($precision = 0) {
		return $precision ? sprintf("%.{$precision}f", self::$_queryTime) : self::$_queryTime;
	}
	
	public static function getQueryTimeTotal($precision = 0) {
		return $precision ? sprintf("%.{$precision}f", self::$_queryTimeTotal) : self::$_queryTimeTotal;
	}
}
