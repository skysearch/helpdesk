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
class Application_Model_Default_Table_Admin_Role extends Sky_Db_Table_Abstract {
    //put your code here
    protected $_name = 'core_role';
    protected $_primary = array('role_id');
    protected $_dependentTables = array('Application_Model_Default_Table_Admin_User');
    
    protected $_dataType = array(
        'name' => array('type'=>'slug'),
    );
    
    protected $_autoDate = true;
}


