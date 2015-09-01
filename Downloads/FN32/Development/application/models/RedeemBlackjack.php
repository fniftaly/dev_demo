<?php
class Application_Model_RedeemBlackjack extends Application_Model_RedeemAbstract {
	public $success = 0;
	public $message;
	
	public function redeem() {
		$code = $this->request()->getPost('code');
		$phone = $this->request()->getPost('phone');
		
		if ($code && $phone) {
			$code = strtoupper($code);
			$phone = Application_Model_Utility::cleanPhone($phone);
			if ($phone[0] != 1 && strlen($phone) == 10) {
				$phone = '1' . $phone;
			}
			
			$sql = "CALL luckderby_add_redemption('$code', $phone)";
			$rs = $this->query($sql);
			if ($rs && $rs->num_rows) {
				$this->success = $rs->success;
				$this->message = $rs->message;
			} else {
				$this->message = 'Could not redeem code ' . $code . '. Please contact textmunication for assistance.';
			}
		} else {
			$this->message = 'A code and a phone number are required.';
		}
	}
}