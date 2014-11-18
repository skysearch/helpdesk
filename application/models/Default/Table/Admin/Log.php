<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author Renato David
 */
class Application_Model_Default_Table_Admin_Log extends Sky_Db_Table_Abstract {
    //put your code here
    protected $_name = 'core_log';
    protected $_primary = array('log_id');
    
    protected $_dataType = array(
        'created' => array('type'=>'datetime')
    );
    
    protected $_autoDate = true;
}


