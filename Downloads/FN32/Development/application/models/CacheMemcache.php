<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CacheMemcache
 *
 * @author farad
 */
class Application_Model_CacheMemcache extends Application_Model_Abstract {
	 
	    public $iTtl; // Time To Live
	    public $bEnabled = false; // Memcache enabled?
	    public $oCache = null;
	 
	    // constructor
            public function __construct($iTtl = 86400) {
	        if (class_exists('Memcache')) {
	            $this->oCache = new Memcache();
	            $this->bEnabled = true;
                          $this->iTtl = $iTtl;
//	            if (! $this->oCache->connect('10.210.65.119', 11211))  { 
	            if (! $this->oCache->connect('10.179.252.160', 11211))  { 
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
