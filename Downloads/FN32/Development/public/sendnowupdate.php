<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

 function sendNow($message = '', Array $recipients = array()) {
//      $apiUrl = 'https://text.mes.syniverse.com/SMSSend';    
//      $username = '4400';
//      $password = 'Fq0^Hc0^';
//      $shortcode = 87365;
        // Validate our username and password
        if (empty($this->_username) || empty($this->_password)) {
            $this->setError('A username and password are required', true);
            return false;
        }
        
        // Validate our message
        if (empty($message)) {
            if (empty($this->_message)) {
                $this->setError('No message body found', true);
                return false;
            }
            
            $message = $this->_message;
        }
        
        // Make sure the message is a string
        $message = "$message";
        
        // Validate our recipient list
        if (empty($recipients)) {
            if (empty($this->_recipients)) {
                $this->setError('No recipients found', true);
                return false;
            }
            
            $recipients = $this->_recipients;
        }
        
        // Make the list an array
        $recipients = (array) $recipients;
        
        // Get our count of recipients
        $recipientsCount = count($recipients);
        
        // Set up our number of fetched recipients for sending
        $recipientsFetched = 0;
        
        // Set up the message to send
        $sendMessage = urlencode($message);
        
        // Get our sent count for handling success
        $sent = 0;
        
        // Error container
        $error = null;
        
        // While we still have recipients yet to fetch, fetch and send
//        while ($recipientsFetched < $recipientsCount) {
            // Set our group of people to send to
            $sendTo = array();
            
            // Set our group fetch count
            $queueFetched = 0;
            
            // Start fetching recipients as long as its within the limit and we
            // have something to fetch
            while ($queueFetched < $this->_queueLimit && ($subscriber = key($recipients)) !== null) {
                // Add to our send to list
                $sendTo[$subscriber] = $recipients[$subscriber];
                
                // Move the array pointer to the next recipient
                next($recipients);
                
                // Increment our queue counter
                $queueFetched++;
                
                // Increment our recipients counter
                $recipientsFetched++;
            }
            $sendToRec = implode('&recipient=', $sendTo);
            $this->sendSMS(87365,$sendToRec,"$sendMessage");            
            $gatewaymessageid = $this->saveOutbound();
    }
?>
