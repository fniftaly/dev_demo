<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of memcache
 *
 * @author farad
 */
class CacheMemcache {
	 
	    public $iTtl = 86400; // Time To Live
	    public $bEnabled = false; // Memcache enabled?
	    public $oCache = null;
//                  public $arr = array();
	 
	    // constructor
            public function __construct() {
	        if (class_exists('Memcache')) {
	            $this->oCache = new Memcache();
	            $this->bEnabled = true;
	            if (! $this->oCache->connect('127.0.0.1', 11211))  { 
	                $this->oCache = null;
	                $this->bEnabled = true;
	            }
	        }
	    }
	 
	    // get data from cache server
	   public function getData($sKey) {
	        $vData = $this->oCache->get($sKey);
	        return false === $vData ? null : $vData;
	    }
	 
	    // save data to cache server
	  public  function setData($sKey, $vData) {
	        //Use MEMCACHE_COMPRESSED to store the item compressed (uses zlib).
	        return $this->oCache->set($sKey, $vData, MEMCACHE_COMPRESSED, $this->iTtl);
	    }
	 
	    // delete data from cache server
	   public function delData($sKey) {
	        return $this->oCache->delete($sKey);
	    }
            
       
}

?>
