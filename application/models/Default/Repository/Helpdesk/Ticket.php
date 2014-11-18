<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Application_Model_Default_Repository_Helpdesk_Ticket extends Sky_Db_Repository_Abstract {

    public function create($data) {
        $func = $this->getTable('Ticket');

        if (!key_exists('prazo', $data)) {
            $Date = Application_Model_Default_Repository_Helpdesk_Ticket_Helpers::getHelper('date',array('categoria'=>$data['assunto']));
            $data['prazo'] = $Date->get('Y-m-d H:i');
        }

        if (!key_exists('prioridade', $data)) {
            $Priority = Application_Model_Default_Repository_Helpdesk_Ticket_Helpers::getHelper('priority');
            $data['prioridade'] = $Priority->get();
        }

        if (!key_exists('status', $data)) {
            $Status = Application_Model_Default_Repository_Helpdesk_Ticket_Helpers::getHelper('status');
            $data['status'] = $Status->get();
        }


        

        /*if (!key_exists('departamento_id', $data)) {
            if (!key_exists('operador_id', $data)) {
                return false;
            }

            $departamento = $Operadores->getDepartamento($data['operador_id']);
            $data['departamento_id'] = $departamento['departamento_id'];
        } else {
            if (!key_exists('departamento_id', $data)) {
                return false;
            }
  
            
        }*/

        /*
         * Recupera informações de quem está logado
         */
        $auth = Zend_Auth::getInstance();
        $info = $auth->getIdentity();
        
        /*
         * Se não for selecionado nenhuma empresa 
         * será utilizado a empresa de quem está cadastrando
         * 
         */
        if (!key_exists('cliente', $data)) {
            $data['cliente'] = implode(',',$info['info']['cliente']);
        }
        
        /*
         * Se não for selecionado nenhuma operado 
         * será sorteadoum operador que atenda aquela empresa
         * 
         */
        $Operadores = Sky_Model_Factory::getInstance()->setModule('helpdesk')->getOperador();
        
        if (!key_exists('operador_id', $data)) {
            $operador = $Operadores->getOperatorByCleinteRand($data['cliente']);
            $data['operador_id'] = $operador['operador_id'];
            $data['departamento_id'] = $operador['departamento_id'];
        } 
        

        $data['proprietario'] = $info['user']['user_id'];

        if(!key_exists('cliente',$data)){
            $data['cliente'] = $info['info']['cliente'];
        }

        
        $id = $func->insert($data);


        if ($id > 0) {
            //$this->setOperador($data['operador_id'], $id);
            return (int) $id;
        }

        return null;
    }

    public function update($data,$id) {
        $func = $this->getTable('Ticket');
        $row = $this->getById($id);
        
        /*$Operadores = Sky_Model_Factory::getInstance()->setModule('helpdesk')->getOperador();

        if (!key_exists('departamento_id', $data)) {
            if (!key_exists('operador_id', $data)) {
                return false;
            }
            if($data['operador_id']!=$row['operador_id']) {
                $departamento = $Operadores->getDepartamento($data['operador_id']);
                $data['departamento_id'] = $departamento['departamento_id'];
            }
        } else {
            if (!key_exists('departamento_id', $data)) {
                return false;
            }
            if($data['departamento_id']!=$row['departamento_id']) {
                $operador = $Operadores->getOperatorRand($data['departamento_id']);
                $data['operador_id'] = $operador['operador_id'];
            }
        }
        
        */
        
        if(key_exists('proprietario', $data)){
            unset($data['proprietario']);
        }
        
        if (!key_exists('prazo', $data)) {
            $Date = Application_Model_Default_Repository_Helpdesk_Ticket_Helpers::getHelper('date',array('categoria'=>$data['assunto']));
            $data['prazo'] = $Date->get('Y-m-d H:i');
        }

        if (!key_exists('prioridade', $data)) {
            $Priority = Application_Model_Default_Repository_Helpdesk_Ticket_Helpers::getHelper('priority');
            $data['prioridade'] = $Priority->get();
        }

        if (!key_exists('status', $data)) {
            $Status = Application_Model_Default_Repository_Helpdesk_Ticket_Helpers::getHelper('status');
            $data['status'] = $Status->get();
        }
        
        
        /*
         * Se não for selecionado nenhuma empresa 
         * será utilizado a empresa de quem está cadastrando
         * 
         */
        if (!key_exists('cliente', $data)) {
            $data['cliente'] = implode(',',$info['info']['cliente']);
        }
     

        $return = $func->update($data,array('ticket_id=(?)'=>$id));


        if ((bool)$return) {
            $this->setOperador($data['operador_id'], $id);
            return (int) $id;
        }

        return null;
    }
    
    public function delete($id){
        return (bool)$this->getTable('Ticket')->find($id)->current()->delete();
    }

    public function setOperador($operador, $ticket) {
        $func = $this->getTable('TicketOperador');
        $id = array();
        

        if (is_array($operador)) {
            foreach ($operador as $id) {
                $id[] = $this->setOperador(array('operador_id' => $id, 'ticket_id' => $ticket));
            }

            return $id;
        }

        $func->delete(array('operador_id=(?)'=>$operador,'ticket_id=(?)'=>$ticket));
        
        $id[] = $func->insert(array('operador_id' => $operador, 'ticket_id' => $ticket));

        return $id;
    }

    public function getById($id,$type='complete') {
        $func = $this->getTable('Ticket');
        
        if($type=='complete') {
            $select = $func->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                            ->setIntegrityCheck(false)->joinLeft(array('c' => $this->getTable('User')), "c.user_id = {$func}.proprietario", array())
                            ->joinLeft(array('o' => $this->getTable('User')), "o.user_id = {$func}.operador_id", array())
                            ->joinLeft(array('ic' => $this->getTable('UserInfo')), "ic.user_info_id = c.user_info_id", array('cliente_nome' => 'ic.name','cliente_email' => 'ic.email'))
                            ->joinLeft(array('io' => $this->getTable('UserInfo')), "io.user_info_id = o.user_info_id", array('operador_nome' => 'io.name','operador_email' => 'io.email'))
                            ->joinLeft(array('d' => $this->getTable('Departamento')), "d.departamento_id = {$func}.departamento_id", array('departamento'=>'nome','departamento_id'))
                            ->joinLeft(array('a' => $this->getTable('Andamento')), "{$func}.ticket_id = a.ticket_id", array('modified'=>'max(a.modified)'))
                            ->group("{$func}.ticket_id")
                            ->where("{$func}.ticket_id=(?)",$id)
                            ->limit(1);

            return $func->fetchRow($select);
            
        } else if($type=='line') {
            return $func->find($id)->current();
        }
    }
    
    /*public function getByOperador($operador_id,$status=null,$start=null,$end=null,$order='prazo DESC') {
        $func = $this->getTable('Ticket');
        
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
        $func = $this->getTable('Ticket');
        
        $select = $func->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                    ->setIntegrityCheck(false);
        
        /*if(key_exists('departamento_id', $options)) {
            $select->where("{$func}.departamento_id=(?)",$options['departamento_id']);
        }*/
        
        if(key_exists('operador_id', $options)) {
            $select->where("{$func}.operador_id=(?)",$options['operador_id']);
        }   
        
        if(key_exists('proprietario', $options)) {
            $select->where("{$func}.proprietario=(?)",$options['proprietario']);
        } 
        
        if(key_exists('status', $options)) {
            $select->where("{$func}.status=(?)",$options['status']);
        }
        
        if(key_exists('cliente', $options)) {
            if(is_array($options['cliente'])){
                $w = array();
                foreach ($options['cliente'] as $c){
                    $w[] = "{$func}.cliente LIKE '{$c}' ";
                }
                $w = implode(" OR ", $w);
                
                $select->where("({$w})");
            } else {
                $select->where("{$func}.cliente LIKE (?)","{$options['cliente']}");
            }
        }
        
        if (key_exists('start',$options) && !empty($options['start'])) {
            $start = explode('/', $options['start']);
            $select->where("DATE({$func}.created) >= '{$start[2]}-{$start[1]}-{$start[0]}'");

            if (key_exists('end',$options) && !empty($options['end'])) {
                $end = explode('/', $options['end']);
                $select->where("DATE({$func}.created) <= '{$end[2]}-{$end[1]}-{$end[0]}'");
            }
        }
        
        if(key_exists('order',$options)) {
            $select->order($options['order']);
        } else {
            $select->order("prazo ASC");
            $select->order("created ASC");
        }
        
        if(key_exists('limit',$options)) {
            $select->limit($options['limit']);
        }
        
        if(key_exists('search',$options)){
            $select->where("({$func}.assunto LIKE '%{$options['search']}%' OR {$func}.descricao LIKE '%{$options['search']}%')");
        }
        
        $select->joinLeft(array('c' => $this->getTable('User')), "c.user_id = {$func}.proprietario", array())
                ->joinLeft(array('o' => $this->getTable('User')), "o.user_id = {$func}.operador_id", array())
                ->joinLeft(array('ic' => $this->getTable('UserInfo')), "ic.user_info_id = c.user_info_id", array('cliente_nome' => 'ic.name','cliente_email' => 'ic.email'))
                ->joinLeft(array('io' => $this->getTable('UserInfo')), "io.user_info_id = o.user_info_id", array('operador_nome' => 'io.name','operador_email' => 'io.email'))
                ->joinLeft(array('d' => $this->getTable('Departamento')), "d.departamento_id = {$func}.departamento_id", array('departamento'=>'nome','departamento_id'))
                ->joinLeft(array('a' => $this->getTable('Andamento')), "{$func}.ticket_id = a.ticket_id", array('last_modified'=>'max(a.modified)'))
                ->group("{$func}.ticket_id")
                ->order("{$func}.created DESC")
                ->order("{$func}.prazo ASC");
        
                
        return $func->fetchAll($select);
    }
    
    
    public function getInGroups($options = array()) {
        $group = array();
        
    }
    
    public function getByIdOperador($operador_id) {
        $func = $this->getTable('Ticket');
        $select = $func->select()->where('operador_id=(?)',$operador_id);
        
        return $func->fetchRow($select);
    }
    
    public function autoFinalize(){
        $func = $this->getTable('Ticket');
        $configs = Sky_Module_Config::getTable('helpdesk');
        
        /**
         * Andamentos
         */
        /*$andamento = $this->getTable('Andamento');
        $select = $andamento->select()
                    ->where('(created < (NOW() - INTERVAL (?) DAY))',$configs['prazo_finalizar'])
                    ->order('created DESC')
                    ->group('ticket_id');
        
        $rows = $andamento->fetchAll($select);

        foreach ($rows as $row){
           $func->update(array('status'=>'chamado-finalizado'),
                array("(status = 'novo-chamado' OR status = 'em-andamento')",
                        "ticket_id={$row['ticket_id']}")
                );      
        }*/
        
        /**
         * Tickets
         */
        $select = $func->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                ->setIntegrityCheck(false)
                ->joinLeft(array('ad' => $this->getTable('Andamento')), "ad.ticket_id = {$func}.ticket_id", 
                        array('ad_andamento'=>'COUNT(ad.ticket_id)','ad_maxcreated'=>'MAX(ad.created)'))
                ->having('ad_andamento > 0')
                ->having('(ad_maxcreated < (NOW() - INTERVAL (?) DAY))',$configs['prazo_finalizar'])       
                ->group("{$func}.ticket_id")        
                ->where("{$func}.status <> 'chamado-finalizado'");
        
        $rows = $func->fetchAll($select);
        
        foreach ($rows as $row){
           $func->update(array('status'=>'chamado-finalizado'),
                array("(status = 'novo-chamado' OR status = 'em-andamento')",
                        "ticket_id={$row['ticket_id']}")
                );      
        }
        
        /**
         * Tickets
         */
        $select = $func->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                ->setIntegrityCheck(false)
                ->joinLeft(array('ad' => $this->getTable('Andamento')), "ad.ticket_id = {$func}.ticket_id", array('ad_andamento'=>'COUNT(ad.ticket_id)'))
                ->having('ad_andamento = 0')
                ->group("{$func}.ticket_id")        
                ->where("({$func}.created < (NOW() - INTERVAL (?) DAY))",$configs['prazo_finalizar'])
                ->where("{$func}.status <> 'chamado-finalizado'");
        
        $rows = $func->fetchAll($select);
        
        //Zend_Debug::dump($select);exit();
                
        foreach ($rows as $row){
           $func->update(array('status'=>'chamado-finalizado'),
                array("(status = 'novo-chamado' OR status = 'em-andamento')",
                        "ticket_id={$row['ticket_id']}")
                );      
        }        
        /*$func->update(array('status'=>'chamado-finalizado'),
                array("(status = 'novo-chamado' OR status = 'em-andamento')",
                        "(created < NOW() - INTERVAL {$configs['prazo_finalizar']} DAY)")
                );*/

    }

}
