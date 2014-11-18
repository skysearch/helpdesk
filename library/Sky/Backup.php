<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Sky_Backup {
    
    const F_TABLE = 'table';
    const F_COMPLETE = 'complete';
    const F_SMART = 'smart';
    
    const F_PART_NAME = 'Tables_in_';
    
    protected $_folder = 'backup';
    protected $_className = 'Sky_Backup_';
    protected $_obj;
    protected $_configs;
    protected $_data;


    public function setType($type) {
        $this->_configs = Sky_Config::getConfig();
        
        $className = "{$this->_className}{$type}";
        $this->_obj = new $className;

        $params = $this->_configs->resources->db->params;
        
        $this->_data = array(
            'request' => $params->host.':'.$params->port,
            'user_name' => $params->username,
            'data_post' => array('adapter'=>$this->_configs->resources->db->adapter,
                                    'version'=>$this->_configs->resources->db->version,
                                    'dbname'=>$params->dbname,
                                    'charset'=>$params->charset,
                                    'profiler'=>$params->profiler,
                                    'persistent'=>$params->persistent),
            'level' => 'INFO',
            'event' => strtoupper($type),
            'type' => Sky_Log::TYPE_SYSTEM,
            'ip' => $params->host,
            'session_id' => ''
        );
        
        
        
        return $this;
    }
    
    public function save(){
        $log = Sky_Model_Factory::getInstance()->getLog();
        $log->setSystem($this->_data);
        
        return $this->_obj->save();
    }
    
    public function lists(){
        return $this->_obj->lists();
    }
}

