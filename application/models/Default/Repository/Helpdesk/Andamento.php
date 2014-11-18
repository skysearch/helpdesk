<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Application_Model_Default_Repository_Helpdesk_Andamento extends Sky_Db_Repository_Abstract {

    public function delete($id) {
       $row = $this->getById($id); 
       return (bool)$row->delete();
    }

    public function insert($data) {
        return $this->getTable('Andamento')->insert($data);
    }

    public function update($data, $id) {
        $func = $this->getTable('Andamento');
        return (bool)$func->update($data,array('andamento_id=(?)' => $id));
    }

    public function getById($id) {
        return $this->getTable('Andamento')->find($id)->current();
    }
    
    public function getByTicket($ticket_id) {
        $func = $this->getTable('Andamento');
        $select = $func->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                    ->setIntegrityCheck(false)
                ->joinLeft(array('c' => $this->getTable('User')), "c.user_id = {$func}.proprietario", array())
                ->joinLeft(array('o' => $this->getTable('User')), "o.user_id = {$func}.operador_id", array())
                ->joinLeft(array('ic' => $this->getTable('UserInfo')), "ic.user_info_id = c.user_info_id", array('proprietario_nome' => 'ic.name','proprietario_email' => 'ic.email'))
                ->joinLeft(array('io' => $this->getTable('UserInfo')), "io.user_info_id = o.user_info_id", array('operador_nome' => 'io.name','operador_email' => 'io.email'))
                ->where("{$func}.ticket_id=(?)",$ticket_id)
                ->order("{$func}.modified DESC")
                ->order("{$func}.created DESC");
        
        $row = $func->fetchAll($select);
        
        if(count($row)>0) {
            return $row;
        }
        
        return null;
    }

    public function count($ticket_id) {
        $func = $this->getTable('Andamento');
        $select = $func->select()->from($func, array('COUNT(*) as total'))->where('ticket_id=(?)',$ticket_id);
        $row = $func->fetchRow($select);
        return (int) $row['total'];
    }


    public function getComboOptions($filter=null,$primary='andamento_id',$value='create'){
        $helper = $this->getHelper('DataCombo');
        return $helper->getDataCombo($primary,$value,$this->getAll($filter));
    }

}