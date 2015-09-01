<?php

class IndexController extends AuthorizedController {
	public function indexAction() {
		//var_dump($this->user); die;
        // Below codes shifted to messagemonthyearAction of reports controller
		/*$this->view->msg_limit   = $this->user->messagelimit;
		$this->view->msg_used    = $this->user->getMessageCount();
		$this->view->msg_percent = sprintf("%d", ($this->view->msg_used / $this->view->msg_limit) * 100);
		$this->view->key_limit   = $this->user->keywordlimit;
		$this->view->key_used    = $this->user->getKeywordsCount();
		$this->view->key_percent = sprintf("%d", ($this->view->key_used / $this->view->key_limit) * 100);
		
		// Large graph
		$dayrange = 30;
		$daystep  =  2;
		$this->view->graphrange   = $dayrange;
		$this->view->graphstep    = $daystep;
		$optins  = $this->user->getTotalSubscriberCountByDay($dayrange, 'asc');
		$optouts = $this->user->getDailyOptoutsByDay($dayrange, 'asc');
		$this->view->graphoptins  = $optins;
		$this->view->graphoptouts = $optouts;*/
              
    }
}

