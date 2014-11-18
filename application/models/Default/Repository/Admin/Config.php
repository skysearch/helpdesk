<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Application_Model_Default_Repository_Admin_Config extends Sky_Db_Repository_Abstract  {

    public function getAll(){
        $func = $this->getTable('Config');
        $select = $func->select()
                    ->order('module ASC')
                    ->order('order');
        
        return $func->fetchAll($select);
    }
    
    public function getConfigByModule($name){
        $func = $this->getTable('Config');
        $select = $func->select()
                    ->where('module=(?)',$name)
                    ->order('module ASC')
                    ->order('order');
        
        $rows = $func->fetchAll($select);
        
        if(count($rows)>0) {
            return $rows;
        }
        
        return null;
    }
    
    
    public function update($data) {
        $func = $this->getTable('Config');
        
        foreach ($data as $key=>$value) {
            if($func->update(array('value'=>$value), array('param=(?)' => $key))===false)
                return false;
        }
        
        return true;
    }
    /*
    public function getComboOptions($primary,$value){
        $func = $this->getTable('Config');
        $helper = $this->getHelper('DataCombo');
        
        $select = $func->select();
        
        return $helper->getDataCombo($primary,$value,$func,$select);
    }
     */
}

