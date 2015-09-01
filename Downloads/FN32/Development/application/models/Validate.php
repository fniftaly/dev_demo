<?php

class Application_Model_Validate {
	public function isRawPassword($password) {
		return strlen($password) >= 6 && strlen($password) <= 20 && strpos($password, ' ') === false && preg_match('#([\w]|[`~!@\#$%\^&*()_\+={}\]\[;:\'",<\.>\?/|])#', $password);
	}
}
