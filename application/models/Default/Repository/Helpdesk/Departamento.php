<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Application_Model_Default_Repository_Helpdesk_Departamento extends Sky_Db_Repository_Abstract {

    public function delete($id) {
       $row = $this->getById($id); 
       return (bool)$row->delete();
    }

    public function insert($data) {

        return $this->getTable('Departamento')->insert($data);
    }

    public function update($data, $id) {
        $func = $this->getTable('Departamento');
        return (bool)$func->update($data,array('departamento_id=(?)' => $id));
    }

    public function getById($id) {
        return $this->getTable('Departamento')->find($id)->current();
    }
    
    public function getAll($where = null, $order = null, $count = null) {

        return $this->getTable('Departamento')->fetchAll($where, $order, $count);
    }

    public function count() {
        $func = $this->getTable('Departamento');
        $select = $func->select()->from($func, array('COUNT(*) as total'));
        $row = $func->fetchRow($select);
        return (int) $row['total'];
    }

    public function getToDataTable($post, $columns, $json = true) {
        $func = $this->getTable('Departamento');
        $helper = $this->getHelper('DataTable');

        $select = $func->select()->order('nome ASC');
        $helper->setColumns($columns);

        if($json)
            return $helper->getJsonDataTable($func, $select, $post, 'departamento_id', array('del', 'edit'));
       else
           return $func->fetchAll($select);
       
    }
    
    public function getComboOptions($filter=null,$primary='departamento_id',$value='nome'){
        $helper = $this->getHelper('DataCombo');
        return $helper->getDataCombo($primary,$value,$this->getAll($filter));
    }
    
    public function getRoleComboOptions($select=null,$primary='role_id',$value='description'){
        $func = $this->getTable('role');
        $helper = $this->getHelper('DataCombo');
        

         $select = $func->select()->order('description','ASC');


        return $helper->getDataCombo($primary,$value,$func->fetchAll($select));
    }
}