<?php
class Application_Model_Redeem extends Application_Model_Abstract {
	public function redeem($name) {
		$modelpath = dirname(realpath(__FILE__));
		$file = $modelpath . '/Redeem' .ucfirst(strtolower($name)) . '.php';
		if (is_readable($file)) {
			require_once $modelpath . '/RedeemAbstract.php';
			require_once $file;
			$class = 'Application_Model_Redeem' . ucfirst(strtolower($name));
			if (class_exists($class, false)) {
				$obj = new $class();
				$obj->redeem();
				$this->success = $obj->success;
				$this->message = $obj->message;
			}
		}
	}
}