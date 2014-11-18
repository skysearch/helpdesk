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
class Application_Model_Default_Table_Admin_Session extends Sky_Db_Table_Abstract {
    //put your code here
    protected $_name = 'core_session';
    protected $_primary = array('session_id');
    protected $_dependentTables = array('Application_Model_Default_Table_Admin_User');
    protected $_dataType = array();
    protected $_autoDate = false;
    protected $_isCachable = false;
    
}

