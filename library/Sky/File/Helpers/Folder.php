<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Sky_File_Helpers_Folder {
    
    protected $_root = null;

    public function __construct($folder=null) {
        $this->set($folder);
    }

    public function set($folder) {
        $this->_root = $folder;
    }

    public function listFiles($dir=null) {
        $dir_path = $this->_root;
        
        if(!is_null($dir)){
            $dir_path = $dir;
        }
        if(!is_dir($dir_path)){
            return null;
        }
        
        $files = array();
        $dir = scandir($dir_path);
        foreach ($dir as $file) {
            if($file[0]=='.'){
                continue;
            }
            
            $files[] = $file;
        } 
        
        return $files;
    }
    
    public function getPath() {
        return  $this->_root;
    }
    
    public function exists($folder=null){
        if(is_null($folder)){
            $folder = $this->_root;
        }
        return (bool)is_dir($folder);
    }
    
    public function create($folder=null) {
        if(is_null($folder)){
            $folder = $this->_root;
        }
        return (bool)mkdir($folder);
    }
    
    public function rename($new,$old=null){
        if(is_null($old)){
            $old = $this->_root;
        }
        
        return (bool)rename($old, $new);
    }
}
