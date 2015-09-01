<?php
class Application_Model_KeywordPollonew extends Application_Model_KeywordAbstract {
	
	/**
	 * Subscriber object for the mobile number that texted in.
	 * 
	 * @var stdClass
	 * @access public
	 */
	public $subscriber;
	
	/**
	 * Link to the rules page for this contest
	 * 
	 * @var string
	 * @access private
	 */
	private $rules_link = '';
	
	/**
	 * Is the subscribers message a valid response?
	 * 
	 * @var bool
	 * @access public
	 */
	public $valid;
	
	/**
	 * Message that was sent by the subscriber
	 * 
	 * @var mixed
	 * @access private
	 */
	private $message;
	
	/**
	 * Folderid to add the subscriber to
	 * 
	 * @var int
	 * @access private
	 */
	private $folderid;
	
	/**
	 * Where this subscriber is located. It will change the response.
	 * 
	 * @var string
	 * @access private
	 */
	private $userContext;
	
	/**
	 * Users selected language.
	 * 
	 * @var string
	 * @access private
	 */
	private $language;
	
	/**
	 * Chain of events goes as follows:
	 *   1) Opt in responds with "reply 1 for english, 2 for spanish"
	 *   2) Subscriber responds
	 *   3) Response is validated and folder is selected
	 *   4) 
	 * 
	 * @access public
	 * @return void
	 */
	public function handle() {
		// message that was sent by the subscriber
		$this->message = $this->_inbound->message;
		
		// For all Pollo, if this is an already optedin subscriber, send back one thing
		if ($this->_keyword instanceof Application_Model_Keyword) {
			// Not going to user the standard autoresponder
			$this->_keyword->usecustomresponse = true;
			// Default the standard autoresponder
			$this->_keyword->response = null;
			
			// See if this mobile number is already a subscriber to this keyword
			$this->subscriber = $this->_keyword->hasSubscriberPhone($this->_inbound->device_address);
			
			// If this subscriber has already opted in to the keyword, look for a language response
			if ($this->subscriber) {
				// Set the subscriber language based on their response
				$this->setLanguage();
				
				// If their response was not valid, let them know and do nothing
				if (!$this->valid) {
					$this->setResponse('Pollo Campero: Invalid response, Reply 1 (for English) Responde 2 (para Espanol)');
				}
				
				// If their response was valid, play game
				$this->play();
				
				// Play method will handle autoresponse, we are all done!
				
			} else {
				// Ask the subscriber what language
				$this->setResponse('Pollo Campero:Reply 1 (for English) Responde 2 (para Espanol)');
			}
		}
		$this->_writeLog(print_r($this->_keyword, 1));
	}
	
	/**
	 * Set the response to send back to the user.
	 * 
	 * @access private
	 * @param string $response
	 * @return void
	 */
	private function setResponse($response) {
		$this->_keyword->response = $response;
	}
	
	/**
	 * See if a subscriber can play in the contest. They can only play once every 7 days.
	 * 
	 * @access private
	 * @return bool
	 */
	private function canPlay() {
		$last_played = '';
		$this->days_to_play = '';
		
		
		Select @LastPlayedDate = (select MAX(SMSLogDate) from SMSLog where mobilephone = @MobilePhone
                                    and isnull(MessageID,0) between 100 and 1900)
		set @LastPlayedDate = ISNULL(@LastPlayedDate,'1/1/2010')
		set @Days = 7 - (DATEDIFF(day, @LastPlayedDate,getdate() )) 
		print 'last played date'
		print @LastPlayedDate
		print @Days
		print 'Number of days'
		if ISNULL(@Days,0) <= 0  
        SET @CanPlay = 1
            else 
            Begin
                  SET @CanPlay = 0
                  set @Days = 7 - (DATEDIFF(day, @LastPlayedDate,getdate() ))
            End
        
        
        // Can't play
        if (!$can_play) {
            if ($this->isEnglish()) {
				$this->setResponse('Pollo Campero: Sorry, you have already played for the week. Play again in '.$this->days_to_play.' days. Rules: '.$this->rules());
			}
			
			if ($this->isSpanish()) {
				$this->setResponse('Pollo Campero: Solamente puede jugar una vez a la semana. Juega otra vez in '.$this->days_to_play.' days. Reglas: '.$this->rules());
			}
		}
	}
	
	/**
	 * Prizes expire in 2 days.
	 * 
	 * @access private
	 * @return string
	 */
	private function expires() {
		return date('m/d/y',strototime('today +2 days'));
	}
	
	/**
	 * Check the orginating keyword to get the location of this subscriber.
	 * 
	 * @access private
	 * @return void
	 */
	private function setContext() {
		if (in_array(strtolower($this->_keyword->keyword), array('pollo122','pollo124','pollo126'))) {
            $this->userContext = 'dallas';
		} else {
			$this->userContext = 'la'
		}
	}
	
	/**
	 * Set the language choice for this user based on their reply.
	 * 
	 * @access private
	 * @return void
	 */
	private function setLanguage() {
		switch ((int) $this->message) {
			case 1:
				$this->valid = true;
				$this->language = 'english';
				break;
			case 2:
				$this->valid = true;
				$this->language = 'spanish';
				break;
			default:
				$this->valid = false;
		}
		
		if ($this->valid) {
			// Save this subscribers language selection
			$this->setSubscriberLanguage($this->subscriber->id, $this->language);
		}
	}
	
	/**
	 * Is this a spanish subscriber.
	 * 
	 * @access private
	 * @return bool
	 */
	private function isSpanish() {
		return strtolower($this->language) == 'spanish';
	}
	
	/**
	 * Is this an english subscriber.
	 * 
	 * @access private
	 * @return bool
	 */
	private function isEnglish() {
		return strtolower($this->language) == 'english';
	}
	
	/**
	 * Set a subscribers languate selection.
	 * 
	 * @access private
	 * @param mixed $language
	 * @return void
	 */
	private function setSubscriberLanguage($subscriberid, $language) {
		$language = $this->escape($language);
		$sql = 'CALL subscriber_set_language({$subscriberid}, '{$language}')';
		$rs  = $this->query($sql);
		
		return true;
	}
	
	/**
	 * See if the grand prize has been given out or not.
	 * 
	 * @access private
	 * @return void
	 */
	private function grandPrizeOver() {
		// grand prize is already done, so no need to code for it.
		return true;
	}
	
	/**
	 * Determins if the subscribers wins or loses.
	 * 
	 * @access private
	 * @return 
	 */
	private function play() {
		if ($this->canPlay()) {
			$chicken = rand(1,50);
			$side    = rand(1,13);
			$desert  = rand(1,10);
			$grand   = rand(1,100);
			
			// Winning #
			$win = rand(1,50);
			
			/*
			if ($win == $chicken) {
				$prize = 'chicken';
			}
			*/
			switch($win) {
				case $chicken:
					$prize = 'chicken';
					break;
				case $desert:
					$prize = 'desert';
					break;
				case $side:
					$prize = 'side';
					break;
				case $grand:
					$prize = 'grand';
					if ($this->grandPrizeOver()) {
						$prize = false;
					}
					break;
				default:
					$prize = false;
			}
			
			return $this->result($prize);
		}
	}
	
	private function rules() {
		return $this->rules_link;
	}
	
	/**
	 * Get the result of the game.
	 * 
	 * @access private
	 * @return void
	 */
	private function result($prize) {
		switch ($prize) {
			case 'grand':
				// Grand Prize is different for different locations
				if (strtolower($this->userContext) == 'la') {
					if ($this->isEnglish()) {
						$this->setResponse('Pollo Campero: INSTANT WINNER 2 LA Galaxy Tix! SEE CASHIER (98AD3). Expires '.$this->expires().'. Rules: '.$this->rules());
					}
					
					if ($this->isSpanish()) {
						$this->setResponse('Pollo Campero: GANADOR!! 2 LA Galaxia Tix! VEA CAJERO (98AD3). Expira '.$this->expires().'. Reglas: '.$this->rules());
					}
				}
				
				if (strtolower($this->userContext) == 'dallas') {
					if ($this->isEnglish()) {
						$this->setResponse('Pollo Campero: Prize 4. Free Dinner Expires '.$this->expires().'. Play again in one week! txt STOP 2opt-out');   
				    }
				    
				    if ($this->isSpanish()) {
				        $this->setResponse('Pollo Campero: Prize 4. Cena Gratiz! Espira: '.$this->expires().'. Jueja otra vez maz en una semana! Reglas: txt STOP Para quitar');
				    }
				}
				break;
			
			case 'chicken':
				if ($this->isEnglish()) {
					$this->setResponse('Pollo Campero: INSTANT WINNER  Free 2 pieces of chicken or 1/4 grilled! Show code: MM101.  Expires '.$this->expires().'. Play again in 7 days!  Rules: '.$this->rules());
				}
				
			    if ($this->isSpanish()) {
					$this->setResponse('Pollo Campero: GANADOR!! 2 piezas o 1/4 pollo a la parrilla! Muestre codigo MM101 Expira '.$this->expires().'. Vuelva a jugar en 7 dias! Reglas: '.$this->rules());
				}
				break;
			
			case 'side':
				if ($this->isEnglish()) {
					$this->setResponse('Pollo Campero: INSTANT WINNER Free individual side! Show code: MM102. Expires '.$this->expires().'. Play again in 7 days! Rules: '.$this->rules());
				}
				
			    if ($this->isSpanish()) {
					$this->setResponse('Pollo Campero:GANADOR!! Acompanamiento individual gratis.Muestre codigo MM102. Expira '.$this->expires().'. Vuelva a jugar en 7 dias! Reglas: '.$this->rules());
				}
				break;
			
			case 'dessert':
				if ($this->isEnglish()) {
					$this->setResponse('Pollo Campero: INSTANT WINNER Free Dessert! Show code: MM103. Expires '.$this->expires().'. Play again in 7 days! Rules: '.$this->rules());
				}
				
			    if ($this->isSpanish()) {
					$this->setResponse('Pollo Campero: GANADOR!! Postre gratis. Mustre codigo MM103. Expira '.$this->expires().'. Vuelva a jugar en 7 dias! Reglas: '.$this->rules());
				}
				break;
			default: // Loser
				if ($this->isEnglish()) {
					$this->setResponse('Pollo Campero: Sorry, you are not a winner. Play again in 7 days. Rules: '.$this->rules());
				}
				
			    if ($this->isSpanish()) {
					$this->setResponse('Pollo Campero: Lo sentimos usted no es un ganador. Juegue nuevamente en 7 dias! Reglas: '.$this->rules());
				}
		}
	}
}