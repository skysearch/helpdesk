<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Sky_Doctrine_Module_Loader {

    /**
     * @var Sky_Doctrine_Module_Loader
     */
    private static $_instance;
    
    /**
     * @var string
     */
    private $_moduleName;
    
    /**
     * @var array
     */
    private $_options;
    

    /**
    * @return Sky_Doctrine_Module_Loader
    */
    public static function getInstance() {
        if (null == self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct() {
        $this->_options = Sky_Config::getConfig();
    }

    /**
    * @return Sky_Doctrine_Module_Loader
    */
    public function setModule($module){
        $this->_moduleName = $module;
        return self::$_instance;
    }
    
    public function getModule(){
        return $this->_moduleName;
    }
    
    public function init(){
        $options = $this->_options->toArray();
        
        require_once 'Doctrine/Common/ClassLoader.php';
        $autoloader = Zend_Loader_Autoloader::getInstance();

        $doctrineAutoloader = array(new \Doctrine\Common\ClassLoader(), 'loadClass');
        $autoloader->pushAutoloader($doctrineAutoloader, 'Doctrine');
        
        $modulePathDb = realpath(APPLICATION_PATH . DS . 'modules'  . DS . $this->getModule() . DS . 'db' . DS);

        $classLoader = new \Doctrine\Common\ClassLoader('Entities', $modulePathDb, 'loadClass');
        $autoloader->pushAutoloader(array($classLoader, 'loadClass'), 'Entities');
        $classLoader = new \Doctrine\Common\ClassLoader('Symfony', realpath(LIBRARY_PATH . DS .  'Doctrine' . DS), 'loadClass');
        $autoloader->pushAutoloader(array($classLoader, 'loadClass'), 'Symfony');


        if (APPLICATION_ENV != "production") {
            $cache = new \Doctrine\Common\Cache\ArrayCache();
        } else {
            $cacheOptions = $options['doctrine']['cache']['backendOptions'];
            $cache = new \Doctrine\Common\Cache\MemcacheCache();
            $memcache = new Memcache;
            $memcache->connect($cacheOptions['servers']['host'], $cacheOptions['servers']['port']);
            $cache->setMemcache($memcache);
        }

        $config = new \Doctrine\ORM\Configuration();
        $config->setMetadataCacheImpl($cache);
        $driverImpl = $config->newDefaultAnnotationDriver($modulePathDb . DS . 'entities');
        $config->setMetadataDriverImpl($driverImpl);
        $config->setQueryCacheImpl($cache);
        $config->setProxyDir($modulePathDb . DS . 'proxies');
        $config->setProxyNamespace('Proxies');

        if (APPLICATION_ENV != "production") {
            $config->setAutoGenerateProxyClasses(true);
        } else {
            $config->setAutoGenerateProxyClasses(false);
        }

        $em = \Doctrine\ORM\EntityManager::create($options['doctrine']['db'], $config);
        Zend_Registry::set('em', $em);
        
        return $em;
    }
    
    
    
}

