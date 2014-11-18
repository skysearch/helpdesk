<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once LIBRARY_PATH.DS.'PHPExcel'.DS.'PHPExcel'.DS.'IOFactory.php';

class Edi_File_Adapter_Xls implements Edi_File_Adapter_Interface {

    protected $_content = array();
    protected $_configs = array();

    public function setConfig($key,$value){
        $this->_configs[$key] = $value;
        
        return $this;
    }
    
    public function setConfigs(array $configs){
        $this->_configs = array_merge_recursive($configs,$this->_configs);
        
        return $this;
    }
    
    public function getConfigs() {
        return $this->_configs;
    }
    
    public function getConfig($key) {
        return $this->_configs[$key];
    }

    public function open($path, array $configs = array()) {
        
        $file = PHPExcel_IOFactory::load($path);
        $registro = array();
        
        if ($file) {
            $registro= $file->getActiveSheet()->toArray(null,true,true,true);
        }
        
        $this->_content = $registro;
        
        return $this;
    }


    public function get() {
        return $this->_content;
    }
    
    public function close() {
        $this->_content = array();
        
        return $this;
    }
    
    public function _normalize($row) {
        $configs = $this->_configs;

        $fields = array();
        for ($i=1;$i<=$configs['cols'];$i++) {
            $fields[] = $row[$configs['config']["field_{$i}"]];
        }
        
        return $fields;
    }

}
