<?php
/**
 * Application_Model_Userentity class.
 * 
 * This is here because users are an entity, but a special kind. The User model
 * does not extend the entity abstract, so this model does and allows you to manage
 * users as an entity. Might need to rethink this later but for now it will do.
 * 
 * @extends Application_Model_Entityabstract
 */
class Application_Model_Userentity extends Application_Model_Entityabstract {
    
    // Will figure out a better way later...
    public function __construct(Application_Model_User $user, $id = null, $loadby = 'id') {
    	parent::__construct($user, $id, $loadby);
    	$this->_typeid = 5;
    }
    
    /**
     * Update a user meta data.
     *
     * @param array $meta Key=>Pair values for location meta data
     * @return bool Status of the update
     */
    public function update(array $meta) {
        // I think this will be moved to Entityabstract.
        // Update each field individually? Or require the API user
        // to send all params and just update all at once?
        
        return true;
    }
    
    public function encryptPassword($password) {
        return md5($password);
//        return base64_encode($password);
    }
    
    /**
     * Update a single meta value.
     * 
     * @access public
     * @return bool
     */
    public function updatePassword($password) {
    	$password = $this->encryptPassword($password);
    	$password = $this->_dbh->real_escape_string($password);
        $sql = "CALL entity_meta_update($this->id, 25, '$password', {$this->user->getId()})";
	$rs = $this->query($sql);
		
        if ($this->hasError()) {
            error_log($this->getError());
            $this->error = 'Unable to update entity meta information.';
            return false;
        }
        
        return true;
    }
    
    /**
     * Return the current status of this user.
     * 
     * @access public
     * @return int Current status id of this user.
     */
    public function getStatus($verbose = true) {
        if ($verbose) {
            $types = $this->getStatusTypes();
            
            foreach ($types as $type) {
                if ($type['id'] == $this->status) return $type['name'];
            }
        }
        
        return $this->status;
    }
    
    /**
     * This is a dangerous function, but I need it right now until
     * I figure out a better way. It will return the base entity 
     * data (most importantly ID's) for all entities that are of the
     * passed type.
     * 
     * @access public
     * @return void
     */
    public function getAll() {
    	$users = array();
    	
    	$sql = "CALL entity_get_type(5)";
    	$rs  = $this->query($sql);
    	
    	foreach ($rs->fetchAll() as $user) {
    		$id = (int) $user['id'];
    		$users[] = new Application_Model_User($id);
    	}
    	
    	return $users;
    }
    
     /**
     * gets industry list back to view
     * 
     * @access public
     * @return array
     * @name getIndustry
     */
    public function getIndustry(){
        $inds = array();
        
            $sql = "CALL getIndustry()";
            $rs = $this->query($sql);
	   
          if ($this->hasError()) {
            error_log($this->getError());
            $this->error = 'Unable to get idnustries.';
            return $rs;
           }
          if ($rs->hasResults()) {
            foreach ($rs->fetchAll() as $name) {
                $inds[$name['id']] = $name['name'];
            }
           }
        return $inds;
        
    }// end of getIndustry;
    
     /* CRM ENTRY STARTS HERE */

    public function getAuth() {
        $username = "wais@textmunication.com";
        $password = "txhd2014";
        $param = "SCOPE=ZohoCRM/crmapi&EMAIL_ID=" . $username . "&PASSWORD=" . $password;
        $ch = curl_init("https://accounts.zoho.com/apiauthtoken/nb/create");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        $result = curl_exec($ch);
        /* This part of the code below will separate the Authtoken from the result.
          Remove this part if you just need only the result */
        $anArray = explode("\n", $result);
        $authToken = explode("=", $anArray['2']);
        $cmp = strcmp($authToken['0'], "AUTHTOKEN");
        echo $anArray['2'] . "";
        if ($cmp == 0) {
            echo "Created Authtoken is : " . $authToken['1'];
            return $authToken['1'];
        }
        curl_close($ch);
    }

    public function postData($auth, $first, $last, $email,  $business,  $address,   $city, $state,  $zip,   $cell,  $office,$title) {
        $xml =
 '<?xml version="1.0" encoding="UTF-8"?>
     <Leads>
        <row no="1">
        <FL val="Company">'.$business.'</FL>
        <FL val="First Name">'.$first.'</FL>
        <FL val="Last Name">'.$last.'</FL>
        <FL val="Title">'.$title.'</FL>
        <FL val="Email">'.$email.'</FL>
        <FL val="Phone">'.$office.'</FL>
        <FL val="Fax">000000000</FL>
        <FL val="Mobile">'.$cell.'</FL>
        <FL val="Lead Source">External Referral</FL>
        <FL val="Lead Status">Not Contacted</FL>
        <FL val="City">'.$city.'</FL>
        <FL val="Street">'.$address.'</FL>
        <FL val="State">'.$state.'</FL>
        <FL val="Zip Code">'.$zip.'</FL>
        <FL val="Description">Sample Description.</FL>
        <FL val="Created Time">'.date('Y-m-d H:i:s').'</FL>
        </row>
  </Leads>';

        $url = "https://crm.zoho.com/crm/private/xml/Leads/insertRecords";
        $query = "authtoken=" . $auth . "&scope=crmapi&newFormat=1&xmlData=" . $xml;
        $ch = curl_init();
        /* set url to send post request */
        curl_setopt($ch, CURLOPT_URL, $url);
        /* allow redirects */
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        /* return a response into a variable */
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        /* times out after 30s */
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        /* set POST method */
        curl_setopt($ch, CURLOPT_POST, 1);
        /* add POST fields parameters */
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query); // Set the request as a POST FIELD for curl.
        //Execute cUrl session
        $response = curl_exec($ch);
        curl_close($ch);
//	    echo $response;
    }

    /* END OF CRM ENTRY */
}
