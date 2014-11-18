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
class Application_Model_Default_Table_Admin_User extends Sky_Db_Table_Abstract {
    //put your code here
    protected $_name = 'core_user';
    protected $_primary = array('user_id');
    protected $_referenceMap     = array(
        'Session' => array(
            'columns'                   => array('session_id'),
            'refTableClass'             => 'Application_Model_Default_Table_Admin_Session',
            'refColumns'                => array('session_id'),
            //Zend_Db_Table::ON_DELETE    => Zend_Db_Table::CASCADE,
            //Zend_Db_Table::ON_UPDATE    => Zend_Db_Table::CASCADE
        ),
        'Info' => array(
            'columns'                   => array('user_info_id'),
            'refTableClass'             => 'Application_Model_Default_Table_Admin_UserInfo',
            'refColumns'                => array('user_info_id'),
            Zend_Db_Table::ON_DELETE    => Zend_Db_Table::CASCADE,
            Zend_Db_Table::ON_UPDATE    => Zend_Db_Table::CASCADE
        ),
        'Role' => array(
            'columns'                   => array('role_id'),
            'refTableClass'             => 'Application_Model_Default_Table_Admin_Role',
            'refColumns'                => array('role_id'),
            //Zend_Db_Table::ON_DELETE    => Zend_Db_Table::CASCADE,
            //Zend_Db_Table::ON_UPDATE    => Zend_Db_Table::CASCADE
        )
     );
    
    protected $_dataType = array(
        'status'   => array('type'=>'bool'),
        'created'  => array('type'=>'datetime'),
        'modified' => array('type'=>'datetime'),
        'password' => array('type'=>'sha1'),
        'grupo'    => array('type'=>'array')
    );
    
    protected $_autoDate = true;
    
}


