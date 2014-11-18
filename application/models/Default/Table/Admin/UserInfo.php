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
class Application_Model_Default_Table_Admin_UserInfo extends Sky_Db_Table_Abstract {
    //put your code here
    protected $_name = 'core_user_info';
    protected $_primary = array('user_info_id');
    protected $_dependentTables = array('Application_Model_Default_Table_Admin_User');
    
    protected $_autoDate = true;
    
    protected $_dataType = array(
        'panelist' => array('type'=>'bool'),
        'cliente' => array('type'=>'array'),
        'confirmed' => array('type'=>'bool')
    );
}


