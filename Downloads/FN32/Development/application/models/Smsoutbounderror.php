<?php
final class Application_Model_Smsoutbounderror {
	/**
	 * An array of possible error strings to be returned based on error code
	 *
	 * @access protected
	 * @var array
	 */
	protected static $_errors = array(
		992 => 'Invalid body: The body of the request is either empty or invalid.',
		994 => 'Invalid short code: The short code of the request is either empty or invalid.',
		998 => 'Invalid request: A component of the request is invalid.',
		999 => 'Internal error: An internal error occurred.',
	);
	
	/**
	 * Gets a string error message based on code
	 *
	 * @access public
	 * @param integer $code The status code
	 * @param string $add Additional message content to add
	 * @static
	 * @return string
	 */
	public static function getError($code, $add = '') {
		if (!isset(self::$_errors[$code])) {
			return 'An error occurrent, please contact Textmunication Support.';
		}
		
		// 998 codes put their string in the description
		if ($code == 998) {
			$return = self::$_errors[998] . ' ' . $add;
		} else {
			$return = self::$_errors[$code];
		}
		
		return $return;
	}
}