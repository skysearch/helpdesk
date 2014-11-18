<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Application_Model_Default_Repository_Downloads_Pasta extends Sky_Db_Repository_Abstract {

    public function create($data) {
        $func = $this->getTable('Pasta');
        
        $auth = Zend_Auth::getInstance();
        $info = $auth->getIdentity();

        $data['proprietario'] = $info['user']['user_id'];

        $id = $func->insert($data);

        return (int) $id;
    }

    public function update($data,$id) {
        $func = $this->getTable('Pasta');

        if(key_exists('proprietario', $data)){
            unset($data['proprietario']);
        }
        //Zend_Debug::dump($data);exit();
        $return = $func->update($data,array('pasta_id=(?)'=>$id));

        return (int) $return;
    }
    
    public function delete($id){
        return (bool)$this->getTable('Pasta')->find($id)->current()->delete();
    }

    public function getById($id,$type='complete') {
        $func = $this->getTable('Pasta');
        
        if($type=='complete') {
            $select = $func->select()
                            ->where("{$func}.pasta_id=(?)",$id)
                            ->limit(1);

            return $func->fetchRow($select);
            
        } else if($type=='line') {
            return $func->find($id)->current();
        }
    }
    
    /*public function getByOperador($operador_id,$status=null,$start=null,$end=null,$order='prazo DESC') {
        $func = $this->getTable('Pasta');
        
        $select = $func->select()
                        ->where('operador_id=(?)',$operador_id);
        
        if(!is_null($status)) {
            $select->where('operador_id=(?)',$operador_id);
        }
        
        if (!is_null($start)) {
            $start = explode('/', $start);
            $select->where("DATE({$func}.created) >= '{$start[2]}-{$start[1]}-{$start[0]}'");

            if (!is_null($end)) {
                $end = explode('/', $end);
                $select->where("DATE({$func}.created) <= '{$end[2]}-{$end[1]}-{$end[0]}'");
            }
        }
        
        if(!is_null($order)) {
            $select->order($order);
        }
        
        return $func->fetchAll($select);
    }*/
    
    public function get($options = array()) {
        $func = $this->getTable('Pasta');
        
        $select = $func->select();
        
        if(key_exists('grupo', $options)) {
            if(is_array($options['grupo'])){
                $where = array();
                foreach ($options['grupo'] as $gpo){
                    $where[] = "({$func}.grupo LIKE '%\"{$gpo}\"%')";
                }
                $where = implode(' OR ', $where);
                $select->where("({$where})");
            } else {
                $select->where("{$func}.grupo LIKE (?)","%\"{$options['grupo']}\"%");
            }
        }

        if (key_exists('start',$options)) {
            $start = explode('/', $options['start']);
            $select->where("DATE({$func}.created) >= '{$start[2]}-{$start[1]}-{$start[0]}'");

            if (key_exists('end',$options)) {
                $end = explode('/', $options['end']);
                $select->where("DATE({$func}.created) <= '{$end[2]}-{$end[1]}-{$end[0]}'");
            }
        }
        
        if(key_exists('order',$options)) {
            $select->order($options['order']);
        }
        
        $select->order("{$func}.created DESC");
        
        return $func->fetchAll($select);
    }
    
    
    public function getInGroups($options = array()) {
        $group = array();
        
    }
    
    public function getByIdOperador($operador_id) {
        $func = $this->getTable('Pasta');
        $select = $func->select()->where('operador_id=(?)',$operador_id);
        
        return $func->fetchRow($select);
    }
    

}
