<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Application_Model_Default_Repository_Admin_Log extends Sky_Db_Repository_Abstract {

    public function setLog($data) {
        $func = $this->getTable('Log');
        $config = Sky_Config::getConfig();

        if (empty($data['data_post'])) {

            $urls = explode(',', $config->log->url);
            $urls = array_map('trim', $urls);


                if (!in_array("{$data['request']['action']}", $urls) && $data['type']==Sky_Log::TYPE_USER) {
                    if (empty($data['event']))
                        return true;
                }
   
        } else {
            $data['data_post'] = serialize($data['data_post']);
            $urls = explode(',', $config->log->post);
            $urls = array_map('trim', $urls);

            
                if (!in_array($data['request']['action'], $urls) && $data['type']==Sky_Log::TYPE_USER)
                    return true;

        }
        

        if(is_array($data['request'])){
            $par = '';
            foreach ($data['request'] as $val) {
                
                if(is_array($val)) {
                    foreach ($val as $k => $v) {
                        $par .= "/{$v}";
                    }
                }
                
                $par .= "/{$val}";
            }
            $data['request'] = $par;
        }
        
        $msgs = '';
        if (is_array($data['event'])) {
            foreach ($data['event'] as $ev => $arr) {
                foreach ($arr as $ms) {
                    $msgs .= strtoupper($ev) . ": {$ms};";
                }
            }
            
            $data['event'] = $msgs;
        }
        

        /*if($data['level']=='ERROR') {
            Zend_Debug::dump($data);exit;
        }*/
        
        $func->insert($data);
        
        unset($data);
    }
    
    
    public function setError($data){
        $func = $this->getTable('Log');

        $msgs = '';
        if (is_array($data['event'])) {
            foreach ($data['event'] as $ev => $arr) {
                foreach ($arr as $ms) {
                    $msgs .= strtoupper($ev) . ": {$ms};";
                }
            }
            
            $data['event'] = $msgs;
        }

        return $func->insert($data);
    }
    
    public function setUser($data){
        $func = $this->getTable('Log');
        $config = Sky_Config::getConfig();
        $request = explode('/', $data['request']);
        
        if (empty($data['data_post'])) {

            $urls = explode(',', $config->log->url);
            $urls = array_map('trim', $urls);

                if (count(array_intersect($request, $urls))===0) {
                    if (empty($data['event']))
                        return true;
                }
   
        } else {
            $data['data_post'] = serialize($data['data_post']);
            $urls = explode(',', $config->log->post);
            $urls = array_map('trim', $urls);
          
                if (((int)count(array_intersect($request, $urls)))===0) 
                    return true;
                
        }
        
        $msgs = '';
        if (is_array($data['event'])) {
            foreach ($data['event'] as $ev => $arr) {
                foreach ($arr as $ms) {
                    $msgs .= strtoupper($ev) . ": {$ms};";
                }
            }
            
            $data['event'] = $msgs;
        }
        

        $func->insert($data);
    }
    
    public function setDb($data){
        $func = $this->getTable('Log');
        
        $data['data_post'] = serialize($data['data_post']);
        
        $func->insert($data);
    }

    public function setSystem($data){
        $func = $this->getTable('Log');
        
        $data['data_post'] = serialize($data['data_post']);
        
        $func->insert($data);
    }

    
    
    /*
     * Get log list
     */
    public function getLogs($type,$start=null,$end=null,$key=null){
        $func = $this->getTable('Log');

        $select = $func->select()->where('type=(?)',$type)->order('created DESC');
        
        if(is_null($start) &&  is_null($end) && is_null($key))
            $select->limit(300);
        
        if (!is_null($start) && !empty($start)) {
            $start = explode('/', $start);
            $select->where("{$func}.created >= '{$start[2]}-{$start[1]}-{$start[0]}'");

            if (!is_null($end) && !empty($end)) {
                $end = explode('/', $end);
                $select->where("{$func}.created <= '{$end[2]}-{$end[1]}-{$end[0]}'");
            }
        }

        if (!is_null($key) && !empty($key)) 
            $select->where("({$func}.event LIKE '%$key%' OR {$func}.request LIKE '%$key%' OR {$func}.user_name LIKE '%$key%' OR {$func}.created LIKE '%$key%')");
       
        return $func->fetchAll($select);
        
    }
    
    
    /*
     * Get log by Id
     */
    public function getById($id){
        $func = $this->getTable('Log');
        $row = $func->find($id);
        
        if(count($row)>0){
            $row = $row->current()->toArray();
            $acl = Sky_Model_Factory::getInstance()->setModule('admin')->getAcl();;            
            $request = explode('/',$row['request']);
            $anteriores = array();
            $posts = array();
            $privileges = $acl->getPrivileges();
            
            if(key_exists(4, $request)) {
                $select_anteriores = $func->select()
                                            //->from($func,array('log_id','data_post'))
                                            ->where("request=(?)",$row['request'])
                                            ->where('log_id<(?)',$id)
                                            ->where('log_id<>(?)',$id)
                                            ->where('type=(?)',$row['type'])
                                            ->order('log_id DESC')
                                            ->limit(1);
                $anteriores_rows = $func->fetchAll($select_anteriores);
                
                if(count($anteriores_rows)>0) {
                    $anteriores = $anteriores_rows->toArray();
                    
                    $i=0;
                    foreach ($anteriores as $value) {
                        $posts[$i] = (is_array($value['data_post']))?unserialize($value['data_post']):$value['data_post'];
                        $posts[$i]['created'] = $value['created']; 
                        $i++;
                    }
                    
                    $row['data_post'] = (is_array($value['data_post']))?unserialize($row['data_post']):$value['data_post'];
                    
                }
                
                
            } 
            //Zend_Debug::dump($request); 
            //Zend_Debug::dump($privileges); 
            return array('log'=>$row,
                         //'privilege'=>$privileges[$request[1]][$request[2]][$request[3]],
                         'anteriores'=>$anteriores,
                         'posts'=>$posts
                         );
        }
        
        return array();
        
    }
    
    
    /*
     * Get log list by Session
     */
    public function getLogsBySession($id,$start=null,$end=null){
        $func = $this->getTable('Log');

        $select = $func->select()->order('created DESC')->where('session_id=(?)',$id);
        
        if(is_null($start) &&  is_null($end))
            $select->limit(300);
        
        if (!is_null($start) && !empty($start)) {
            $start = explode('/', $start);
            $select->where("{$func}.created >= '{$start[2]}-{$start[1]}-{$start[0]}'");

            if (!is_null($end) && !empty($end)) {
                $end = explode('/', $end);
                $select->where("{$func}.created <= '{$end[2]}-{$end[1]}-{$end[0]}'");
            }
        }
        
        return $func->fetchAll($select);
        
    }
    
}

