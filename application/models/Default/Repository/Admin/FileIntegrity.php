<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Application_Model_Default_Repository_Admin_FileIntegrity extends Sky_Db_Repository_Abstract  {


    public function delete($id) {
    	$func = $this->getById($id);
    	return (bool) $func->delete();
    }
    
    
    public function insert($data) {
        if(is_array($data)){
            $tabele =  $this->getTable('IntegrityHashes');
            $tabele->delete(array('file_hash<>?'=>''));
            
            $ids = array();
            
            foreach ($data as $key => $value) {
                $ids[] = $tabele->insert(array('file_path'=>$key,'file_hash'=>$value));
            }
        }
        
        return $ids;
    }

    

    public function getAll($where = null, $order = null, $count = null) {
        return $this->getTable('IntegrityHashes')->fetchAll($where = null, $order = null, $count = null);
    }
}

