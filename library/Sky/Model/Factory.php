<?php

class Sky_Model_Factory {

    protected $_module = null;

    /**
     * @var Sky_Model_Dao_Factory
     */
    private static $_instance;

    /**
     * Database adapter
     * @var string
     */
    protected $_dbVersion;

    private function __construct() {
        $this->_dbVersion = Sky_Db::getDbVersion();
    }
    
    /**
     * Get database version
     * @return string
     */
    public function getVersion($version){
        $this->_dbVersion = $version;

        return $this;
    }
    

    /**
     * @return Sky_Model_Factory
     */
    public static function getInstance() {
        if (null == self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Reset the module and widget
     */
    public function reset() {
        $this->_dbVersion = null;
    }

    /**
     * Get module name
     * @return string
     */
    public function setModule($module){
        $this->_module = $module;

        return $this;
    }

    /**
     * Get class instance from Repository
     * @param string name, array arguments
     * @return Sky_Db_Repository
     */
    public function __call($name, $arguments) {
        $name = substr($name, 3);
        //$name = substr($name, 0, -3);
        $name = ucfirst($name);
        $module = '';
         
        if(!is_null($this->_module)){
            $module = $this->_normalize($this->_module);
            
            $arguments['table'] = $module;
            $module = $module.'_';
        } 
        
        $class = 'Application_Model_' . $this->_dbVersion . '_Repository_' . $module . $name;

        $model = new $class($arguments);
        return $model;
    }
    
    
    protected function _normalize($name){
        $name = ucfirst($name);
        
        return $name;
    }

}
