<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Edi_File_Reader {

    static protected  $_class = null;
    static protected $_config;

    static function factory($adapter, $config = array()) {
        $name = ucfirst(strtolower($adapter));
        $class = "Edi_File_Adapter_{$name}";
        $configs = Sky_Model_Factory::getInstance()->setModule('Varejo')->getArquivoConfig();
        
        self::$_class = new $class;
        
        if(!key_exists('cols', $config)){
            $config['cols'] = $configs->getDefaultCols();
        }
        
        self::$_class->setConfigs($config);
    }

    /*protected function _normalize($row) {
        $configs = self::$_config;

        $fields = array();
        for ($i=1;$i<=self::$_cols;$i++) {
            $fields[] = $row[$configs['config']["field_{$i}"]-1];
        }
        
        return $fields;
    }*/
    
    public function setConfig($key,$value){
        return self::$_class->setConfig($key,$value);
    }

    public function setConfigs(array $configs){
        return self::$_class->setConfigs($configs);
    }
    
    public function getConfigs(){
        return self::$_class->getConfigs();
    }

    public function getConfig($key){
        return self::$_class->getConfig($key);
    }
    
    public function open($path) {
        
        return self::$_class->open($path);
    }
    
    public function get() {
        return self::$_class->get();
    }
    
    public function close() {
        return self::$_class->close();
    }

    public function getFile() {
        $file = $this->get();
        $content = array();
        
        if(count($file)>0){
            foreach ($file as $line) {
                $content[] = self::$_class->_normalize($line);
            }
        }
        
        return $content;
    }

}
