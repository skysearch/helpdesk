<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Sky_File_Helpers_File {
    
    protected $_file;

    public function set($file) {
        $this->_file = $file;
    }
    
    public function getFile($file=null,$type='array') {
        if(is_null($file)){
            $file = $this->_file;
        }
        if($type=='array'){
            return file($file);
        } else if($type=='string'){
            return file_get_contents($file);
        }
    }

    public function getMime($file=null){
        if(is_null($file)){
            $file = $this->_file;
        }
        $ext = $this->getExt($file);
        $mimes = $this->_loadMimeType();
        
        return $mimes[$ext];
    }
    
    public function getExt($file=null){
        if(is_null($file)){
            $file = $this->_file;
        }
        $file = explode('.', $file);
        
        return strtolower(end($file));
    }
    
    public function getName($file) {
        if(is_null($file)){
            $file = $this->_file;
        }
        $file = explode(DS, $file);
        
        return end($file);
    }
    
    public function rename($new,$old){
        if(is_null($old)){
            $old = $this->_file;
        }
        
        return (bool)rename($old, $new);
    }
    
    public function getPath() {
        return $this->_file;
    }
    
    public function isValid($params=array()){
        
    }
    
    public function exists($file=null){
        if(is_null($file)){
            $file = $this->_file;
        }
        if(file_exists($file) && !is_dir($file)){
            return true;
        }
        
        return false;
    }
    
    public function download($file=null,$name=null){
        if(is_null($file)){
            $file = $this->_file;
        }
        if(is_null($name)){
            $name = $file;
        }
        
        $name = $this->getName($name);
        $mime = $this->getMime($name);

        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename="'.$name.'"');
        header('Content-Type: '.$mime['mime']);
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($file));
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Expires: 0');
        
        readfile($file);
    }
    
    public function upload(){
        
    }
    
    protected function _loadMimeType() {
        $mimes = file('mime'.DS.'types.csv');
        $type = array();
        foreach ($mimes as $row){
            $line = explode(';', $row);
            $type[$line[0]] = array('application'=>$line[1],'mime'=>$line[2]);
        }
        
        return $type;
    }
}