<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Application_Model_Default_Repository_Downloads_Arquivo extends Sky_Db_Repository_Abstract {

    public function delete($id) {
       $row = $this->getById($id); 
       return (bool)$row->delete();
    }

    public function insert($data) {
        return $this->getTable('Arquivo')->insert($data);
    }

    public function update($data, $id) {
        $func = $this->getTable('Arquivo');
        return (bool)$func->update($data,array('arquivo_id=(?)' => $id));
    }

    public function getById($id) {
        return $this->getTable('Arquivo')->find($id)->current();
    }
    
    public function getByPasta($pasta_id) {
        $func = $this->getTable('Arquivo');
        $select = $func->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                ->where("{$func}.pasta_id=(?)",$pasta_id)
                ->order("{$func}.modified DESC")
                ->order("{$func}.created DESC");
        
        $row = $func->fetchAll($select);
        
        if(count($row)>0) {
            return $row;
        }
        
        return null;
    }

    public function count($pasta_id) {
        $func = $this->getTable('Arquivo');
        $select = $func->select()->from($func, array('COUNT(*) as total'))->where('pasta_id=(?)',$pasta_id);
        $row = $func->fetchRow($select);
        return (int) $row['total'];
    }


    public function getComboOptions($filter=null,$primary='arquivo_id',$value='create'){
        $helper = $this->getHelper('DataCombo');
        return $helper->getDataCombo($primary,$value,$this->getAll($filter));
    }

}