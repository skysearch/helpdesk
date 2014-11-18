<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Sky_Db extends Zend_Db {
    
    
    static public function getDbVersion(){
        $config = Sky_Config::getConfig();
        return ucwords(str_replace('_', ' ', strtolower($config->resources->db->version)));
    }
}
?>
