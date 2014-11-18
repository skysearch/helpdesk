<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Application_Model_Default_Repository_Downloads_Operador extends Sky_Db_Repository_Abstract {

    public function getById($id) {
        return $this->getTable('Operador')->find($id)->current();
    }

    public function getByDepartamento($departamento_id) {
        $func = $this->getTable('Operador');
        $select = $func->select()->where('departamento_id=(?)', $departamento_id);

        $row = $func->fetchAll($select);

        if (count($row) > 0) {
            return $row;
        }

        return null;
    }

    public function getDepartamento($id) {
        $func = $this->getTable('User');

        $select = $func->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                ->setIntegrityCheck(false)
                ->join(array('dp' => $this->getTable('Role')), "dp.role_id = {$func}.role_id", array('departamento_id' => 'dp.role_id'))
                ->where("{$func}.user_id=(?)", $id)
                ->limit(1);

        $row = $func->fetchRow($select);

        if (count($row) > 0) {
            return $row;
        }

        return null;
    }

    public function getOperatorRand($departamento_id) {
        $func = $this->getTable('Departamento');

        $select = $func->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                ->setIntegrityCheck(false)
                ->join(array('op' => $this->getTable('User')), "op.role_id = {$func}.departamento_id", array('departamento_id' => 'op.role_id', 'operador_id' => 'op.user_id', 'username'))
                ->where("{$func}.departamento_id=(?)", $departamento_id)
                ->order('RAND()')
                ->limit(1);


        $row = $func->fetchRow($select);

        if (count($row) > 0) {
            return $row;
        }

        $func = $this->getTable('Role');

        $select = $func->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                ->setIntegrityCheck(false)
                ->join(array('op' => $this->getTable('User')), "op.role_id = {$func}.role_id", array('departamento_id' => 'op.role_id', 'operador_id' => 'op.user_id', 'username'))
                ->where("{$func}.name=(?)", 'administrador')
                ->order('RAND()')
                ->limit(1);


        $row = $func->fetchRow($select);


        return $row;
    }

    public function getAll($where = null, $order = null, $count = null) {

        return $this->getTable('Operador')->fetchAll($where, $order, $count);
    }

    public function count() {
        $func = $this->getTable('Operador');
        $select = $func->select()->from($func, array('COUNT(*) as total'));
        $row = $func->fetchRow($select);
        return (int) $row['total'];
    }

    public function getToDataTable($post, $columns, $json = true) {
        $func = $this->getTable('Operador');
        $helper = $this->getHelper('DataTable');

        $select = $func->select()->order('nome ASC');
        $helper->setColumns($columns);

        if ($json)
            return $helper->getJsonDataTable($func, $select, $post, 'operador_id', array('del', 'edit'));
        else
            return $func->fetchAll($select);
    }

    public function getComboOptions($filter = null, $primary = 'operador_id', $value = 'nome') {
        $helper = $this->getHelper('DataCombo');
        return $helper->getDataCombo($primary, $value, $this->getAll($filter));
    }

    public function getUserComboOptions($select = null, $primary = 'user_id', $value = 'name') {
        $func = $this->getTable('user');
        $helper = $this->getHelper('DataCombo');

        $select = $func->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                ->setIntegrityCheck(false)
                ->join(array('dp' => $this->getTable('Departamento')), "dp.departamento_id = {$func}.role_id", array())
                ->joinLeft(array('ui' => $this->getTable('UserInfo')), "ui.user_info_id = {$func}.user_info_id", array('name' => 'ui.name'));

        return $helper->getDataCombo($primary, $value, $this->getAll($select));
    }

}
