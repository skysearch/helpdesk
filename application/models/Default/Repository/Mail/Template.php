<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Application_Model_Default_Repository_Mail_Template extends Sky_Db_Repository_Abstract  {
   
    public function getById($id) {
        return $this->getTable('Template')->find($id)->current();
    }
    
    public function getByName($name) {
        $func = $this->getTable('Template');
        $select = $func->select()->where('name=(?)',$name);
        
        return $func->fetchRow($select);
    }
    
    public function getAll() {
        $func = $this->getTable('Template');

        return $func->fetchAll();
    }

    public function count() {
        $func = $this->getTable('Template');
        $select = $func->select()->from($func, array('COUNT(*) as total'));
        $row = $func->fetchRow($select);
        return (int) $row['total'];
    }
    
    public function delete($id) {
        $func = $this->getById($id);
        if(count($func)){
            return (bool) $func->delete();
        }
        
        return false;
    }

    
    protected function _filterHtml($data){
        $data['content'] = HTMLPurifier($data['content']);

        return $data;
    }


    public function insert($data) {
        $data = $this->_filterHtml($data);
        
        return (int) $this->getTable('Template')
                          ->insert($data);
    }

    public function update($data, $id) {
        $data = $this->_filterHtml($data);
        
        return (bool) $this->getTable('Template')
                           ->update($data, array("template_id=(?)" => $id));
    }

    public function getToDataTable($post, $columns) {
        $func = $this->getTable('Template');
        $helper = $this->getHelper('DataTable');

        $select = $func->select()->order('name ASC');


        $helper->setColumns($columns);

        return $helper->getJsonDataTable($func, $select, $post,'template_id',array('del','edit'));
    }
    
    public function getComboOptions($primary,$value){
        $func = $this->getTable('Template');
        $helper = $this->getHelper('DataCombo');
        
        $select = $func->select();
        
        return $helper->getDataCombo($primary,$value,$func,$select);
    }
    
}

