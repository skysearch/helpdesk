<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Application_Model_Default_Repository_Helpdesk_Ticket_Helpers_Date {
    
    protected $_default;


    public function __construct($config = array()) {
        $configs = Sky_Module_Config::getTable('helpdesk');
        
        $date = new Zend_Date();
        if(key_exists('categoria', $config)){
            $hour = $this->_getHourByAssunto($config['categoria']);
            $date->addHour($hour);
        } else {
            $date->addHour($configs['helpdesk_prazo']);
        }
        
        $this->_default = $date;
    }
    
    protected function _getHourByAssunto($name){
        $Categorias = Sky_Model_Factory::getInstance()->setModule('admin')->getCategoria();
        $row = $Categorias->getByName($name);
        
        return (int)$row['extra'];
    }

    public function set($default) {
        $date = new Zend_Date();
        $date->addHour($default);
        
        $this->_default = $date;
        
        return $this;
    }
    
    public function get() {
        return $this->_default;
    }
}
