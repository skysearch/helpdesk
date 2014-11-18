<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Application_Model_Default_Repository_Admin_Categoria extends Sky_Db_Repository_Abstract {

    

    public function delete($id) {
       $row = $this->getById($id); 
       return (bool)$row->delete();
    }

    public function insert($data) {
        return $this->getTable('Categoria')->insert($data);
    }

    public function update($data, $id) {
        $func = $this->getTable('Categoria');
        return (bool)$func->update($data,array('categoria_id=(?)' => $id));
    }

    public function getByName($name) {
        if(empty($name)) {
            return "";
        }
        $func = $this->getTable('Categoria');
        $select = $func->select()->where('name=(?)', $name);

        
        return $func->fetchRow($select);
    }
    
    public function translate($text){
        $row = $this->getByName($text);
        if(count($row)>0){
            return $row['description'];
        }
        
        return $text;
    }

    public function getById($id) {
        return $this->getTable('Categoria')->find($id)->current();
    }
    
    public function getByParent($name) {
        $func = $this->getTable('Categoria');
        $select = $func->select()->where('parent=(?)', $name);

        return $func->fetchAll($select);
    }
    
    public function getByFilter($name=null) {
        $func = $this->getTable('Categoria');
        $select = null;
        
        if(!is_null($name)){
            $select = $func->select()
                    ->where('filter=(?)', $name)
                    ->order('order ASC')
                    ->order('name ASC');
        }
        
        return $func->fetchAll($select);
    }

    public function getAll($where = null, $order = null, $count = null) {

        return $this->getTable('Categoria')->fetchAll($where, $order, $count);
    }

    public function count() {
        $func = $this->getTable('Categoria');
        $select = $func->select()->from($func, array('COUNT(*) as total'));
        $row = $func->fetchRow($select);
        return (int) $row['total'];
    }

    public function getToDataTable($post, $columns, $json = true) {
        $func = $this->getTable('Categoria');
        $helper = $this->getHelper('DataTable');

        $select = $func->select()->order('filter ASC')->order('name ASC')->order('parent ASC');
        $helper->setColumns($columns);

        if($json)
            return $helper->getJsonDataTable($func, $select, $post, 'categoria_id', array('del', 'edit'));
       else
           return $func->fetchAll($select);
       
    }
    
    public function getComboOptions($filter=null,$primary='name',$value='description'){
        $helper = $this->getHelper('DataCombo');
        return $helper->getDataCombo($primary,$value,$this->getByFilter($filter));
    }
}