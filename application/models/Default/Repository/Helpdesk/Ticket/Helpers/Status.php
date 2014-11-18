<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Application_Model_Default_Repository_Helpdesk_Ticket_Helpers_Status {
    
    protected $_default;


    public function __construct() {
        $configs = Sky_Module_Config::getTable('helpdesk');
        
        $this->_default = $configs['helpdesk_status'];
    }
    
    public function set($default) {

        $this->_default = $default;
        
        return $this;
    }
    
    public function get() {
        return $this->_default;
    }
}