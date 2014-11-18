<?php

class Helpdesk_View_Helper_LimitDate extends Zend_View_Helper_Abstract 
{
    public function limitDate($date){
        $now = new DateTime();
        $configs = Sky_Module_Config::getTable('helpdesk');
        if(!($date instanceof DateTime)) {
           $date = new DateTime($date); 
        }
        
        $date->modify("+{$configs['prazo_reabrir']} day");
        
        if($date>=$now){
            return true;
        }
        
        return false;
    }
}
