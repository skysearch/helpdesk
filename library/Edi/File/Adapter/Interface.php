<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
interface Edi_File_Adapter_Interface {
    
    public function setConfig($key,$value);
    public function setConfigs(array $configs);
    public function getConfigs();
    public function getConfig($key);
    public function open($path, array $configs );
    public function get();
    public function close();
    public function _normalize($row);
    
}