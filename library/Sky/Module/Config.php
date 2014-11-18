<?php

class Sky_Module_Config {

    const KEY = 'Sky_Config_Module_';


    /**
     * Get application config object
     * 
     * @return Zend_Config
     */
    public static function getConfig($module, $controller = null) {
        $key = self::KEY;
        $config = 'config.ini';
        $cache = Sky_Cache::getInstance();
        
        $config_sys = Sky_Config::getConfig();
        
        if(!is_null($controller)) {
            $key = $key . '_Controller_' . $controller;
            $config = strtolower($controller) . '.ini';
        }
        
        if (!Zend_Registry::isRegistered($key)) {
            $file = APPLICATION_PATH . DS . 'modules' . DS . $module . DS . 'configs' . DS . $config;

            if (!file_exists($file)) {
                return null;
            }
            
            if($config_sys->cache->caching) {
                if (($config = $cache->load(strtolower($key.$module))) === false) {
                    $config = new Zend_Config_Ini($file);
                    $cache->save($config,strtolower($key.$module));
                }
                
            } else {
                $config = new Zend_Config_Ini($file);
            }
            
            Zend_Registry::set($key, $config);
        }

        return Zend_Registry::get($key);
    }
    
    
    /**
     * Get table module config 
     * 
     * @return $config array
     */
    public static function getTable($module) {
        $key = self::KEY . '_' . ucfirst($module) . '_Table';
        $cache = Sky_Cache::getInstance();
        
        $config_sys = Sky_Config::getConfig();
        
        if (!Zend_Registry::isRegistered($key)) {
            if (($config = $cache->load(strtolower($key))) === false) {
                
                $class = Sky_Model_Factory::getInstance()->setModule('admin')->getConfig();
                $configs = $class->getConfigByModule($module);
                
                foreach ($configs as $row) {
                    $config[$row['param']] = $row['value'];
                }
                
                if($config_sys->cache->caching) {
                    $cache->save($config,strtolower($key));
                } 
            }
            
            Zend_Registry::set($key, $config);
        }
        
        return Zend_Registry::get($key);
    }

}
