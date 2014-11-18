<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Application_Model_Default_Repository_Helpdesk_Ticket_Helpers {
    
    static public function getHelper($helper,$config = array()) {
        $name = ucfirst(strtolower($helper));
        include APPLICATION_PATH.DS.'models'.DS.Sky_Db::getDbVersion().DS.'Repository'.DS.'Helpdesk'.DS.'Ticket'.DS.'Helpers'.DS."{$name}.php";
        
        $class = "Application_Model_Default_Repository_Helpdesk_Ticket_Helpers_{$name}";
        
        return new $class($config);
    }
    
}
