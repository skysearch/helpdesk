<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Admin_View_Helper_RoleOnly extends Zend_View_Helper_Abstract {
    
    public function roleOnly($role){
        $auth = Zend_Auth::getInstance();
        
        if(!$auth->hasIdentity()) 
            return false;
        
        $info = $auth->getIdentity();
        if($info['role']['name'] == $role) {
            return true;
        }
        
        return false;
    }
}

