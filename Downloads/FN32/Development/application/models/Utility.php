<?php

class Application_Model_Utility {
	public static function getRandomString($length = 32, $all = true) {
		// These are our allowable chars (default set)
		$chars='abcdefghijklmnopqrstuvwxyz0123456789';
		
		// If we want to use all available chars...
		if ($all) {
			// ... we add in the Upper Case group of alphas as well
			$chars .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		}
		
		// Set the rand max paramter
		$max = strlen($chars)-1;
		
		// Start the return string build
		$return = '';
		
		// Shrink down length to zero as we build ...
		while ($length-- > 0) {
			// ... random number
			$return .= $chars{mt_rand(0, $max)};
		}
		
		return $return;
	}
	
	public static function password($string) {
		return hash('sha256', (string) $string);
	}
	
	public static function formatPhoneNumber($phonenumber, $validate = false) {
		if (!$validate) {
			return self::getFormattedPhoneNumber($phonenumber);
		}
		
		if ($phonenumber) {
			if ($phonenumber[0] != 1) {
				$phonenumber = '1' . $phonenumber;
			}
			
			if (strlen($phonenumber) == 11) {
				return self::getFormattedPhoneNumber($phonenumber);
			}
		}
		
		return null;
	}
	
	public static function logTmpFile($filename, $data) {
		$fh = fopen($filename, 'a');
		fwrite($fh, $data);
		fclose($fh);
	}
    
    public static function cleanPhone($phonenumber) {
        return preg_replace("/[^0-9]/", "", $phonenumber);
    }
    
    public static function getFormattedPhoneNumber($phonenumber) {
    	$countrycode = substr($phonenumber,0,1);
		$areacode    = substr($phonenumber,1,3);
		$prefix      = substr($phonenumber,4,3);
		$suffix      = substr($phonenumber,7,4);
		
		return $countrycode.' ('.$areacode.') '.$prefix.'-'.$suffix;
    }
}
