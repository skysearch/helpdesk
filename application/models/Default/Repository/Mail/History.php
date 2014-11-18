<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Application_Model_Default_Repository_Mail_History extends Sky_Db_Repository_Abstract  {
   
    public function getById($id) {
        return $this->getTable('History')->find($id)->current();
    }
    
    public function getByFrom($from) {
        $func = $this->getTable('History');
        $select = $func->select()->where('from=(?)',$from);
        
        return $func->fetchRow($select);
    }
    
    public function getByTo($to) {
        $func = $this->getTable('History');
        $select = $func->select()->where('to=(?)',$to);
        
        return $func->fetchRow($select);
    }
    
    public function getAll() {
        $func = $this->getTable('History');

        return $func->fetchAll();
    }

    public function count() {
        $func = $this->getTable('History');
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
        $data['message'] = HTMLPurifier($data['message']);
        
        return $data;
    }


    public function insert($data) {
        $data = $this->_filterHtml($data);
        
        return (int) $this->getTable('History')
                          ->insert($data);
    }

    public function getToDataTable($post, $columns) {
        $func = $this->getTable('History');
        $helper = $this->getHelper('DataTable');

        $select = $func->select()->order('created DESC');


        $helper->setColumns($columns);

        return $helper->getJsonDataTable($func, $select, $post,'history_id',array('edit'));
    }
    
}

