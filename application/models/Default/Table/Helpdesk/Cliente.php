<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Application_Model_Default_Table_Helpdesk_Cliente extends Sky_Db_Table_Abstract {
    
    protected $_name = 's_cliente';
    protected $_primary = array('id');
    
    protected $_dataType = array(
        'data_cadastro' => array('type'=>'datetime'),
    );
    
}
