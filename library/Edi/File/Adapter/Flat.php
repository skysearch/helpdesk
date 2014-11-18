<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Edi_File_Adapter_Flat implements Edi_File_Adapter_Interface {

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

    public function open($path, array $configs = array('delimiter'=>';','enclosure'=>'"')) {
        
        $configs = array_merge_recursive($configs,  $this->_configs);
        
        $file = fopen($path, 'r');
        if ($file) {
            while (!feof($file)) {
                $linha = fgets($file);
                if (!$linha) {
                    continue;
                }
                $registro[]= $linha;
            }
            fclose($file);
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
            $col = explode(',', $configs['config']["field_{$i}"]);
            
                
            $fields[] = substr($row, $col[0]-1, $col[1]);
        }

        return $fields;
    }

}
