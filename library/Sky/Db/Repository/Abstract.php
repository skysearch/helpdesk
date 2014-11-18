<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Sky_Db_Repository_Abstract {
    
    protected $_module = null;


    public function __construct($configs=null) {
        if(!is_null($configs)){
            if(key_exists('table', $configs))
                $this->_module = $configs['table'];
        }
    }

    public function __toString() {
        return __CLASS__;
    }

    static public function getHelper($helper) {
        $helper = "Sky_Db_Repository_Helper_{$helper}";
        
        return new $helper;
    }
    
    public function getTable($table){
        $dbVersion = Sky_Db::getDbVersion();
        $table = $this->_normalize($table);
        $module = '';
        
        if(!is_null($this->_module)){
            $module = $this->_normalize($this->_module);
            $module = $module.'_';
        } 
        
        $class = "Application_Model_{$dbVersion}_Table_" . $module . "{$table}";
        return new $class();
    }
    
    
    protected function _normalize($name){
        $name = ucfirst($name);
        
        return $name;
    }
    
}

