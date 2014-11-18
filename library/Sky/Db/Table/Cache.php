<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Sky_Db_Table_Cache {

    static protected $_instance;
    protected $_configs = null;
    protected $_cache = null;
    protected $_isCachable = true;

    /**
     * @return Tomato_Db_Connection_Abstract
     */
    private function __construct() {
        $configs = Sky_Config::getConfig()->toArray();
        //$this->_isCachable = (bool)$configs['cache']['caching'];
        $this->_configs = $configs;
    }

    /**
     * @return Zend_Db
     */
    public static function getInstance() {
        if (null == self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /*
     * 
     */

    public function setCachable($value) {
        if(!($this->_configs['cache']['caching'])) {
            $this->_isCachable = false;
        } else {    
            $this->_isCachable = (bool)$value;
        }
    }


    /*
     * 
     */

    public function isCachable() {
        return $this->_isCachable;
    }

    /*
     * 
     */

    public function cleanCacheAll() {
        Sky_Cache::getInstance()->clean(Zend_Cache::CLEANING_MODE_ALL);
    }

    /*
     * 
     */

    public function clean($tag) {
        if(!is_array($tag))
            $tag = array($tag);
        
        Sky_Cache::getInstance()->clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,$tag);
    }

    /*
     * 
     */

    public function load($key) {
        if (!($this->_isCachable))
            return false;
        
        $key = $this->_createKey($key);
        return Sky_Cache::getInstance()->load($key);
    }

    
    public function save($key,$value,$tags){
        if (!($this->_isCachable))
            return false;

        $key = $this->_createKey($key);
        Sky_Cache::getInstance()->save($value,$key,$tags);
    }


    protected function _createKey($key) {
        if ($key instanceof Zend_Db_Select)
            $key = $key->__toString();

        return $this->_configs['resources']['db']['params']['dbname'] . '_' . md5($key) . '_query';
    }
}


