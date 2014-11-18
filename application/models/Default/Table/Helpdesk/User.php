<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Application_Model_Default_Table_Helpdesk_User extends Application_Model_Default_Table_Admin_User {
    protected $_referenceMap     = array(
        'Session' => array(
            'columns'                   => array('session_id'),
            'refTableClass'             => 'Application_Model_Default_Table_Helpdesk_Session',
            'refColumns'                => array('session_id'),
            //Zend_Db_Table::ON_DELETE    => Zend_Db_Table::CASCADE,
            //Zend_Db_Table::ON_UPDATE    => Zend_Db_Table::CASCADE
        ),
        'Info' => array(
            'columns'                   => array('user_info_id'),
            'refTableClass'             => 'Application_Model_Default_Table_Helpdesk_UserInfo',
            'refColumns'                => array('user_info_id'),
            //Zend_Db_Table::ON_DELETE    => Zend_Db_Table::CASCADE,
            //Zend_Db_Table::ON_UPDATE    => Zend_Db_Table::CASCADE
        ),
        'Role' => array(
            'columns'                   => array('role_id'),
            'refTableClass'             => 'Application_Model_Default_Table_Helpdesk_Role',
            'refColumns'                => array('role_id'),
            //Zend_Db_Table::ON_DELETE    => Zend_Db_Table::CASCADE,
            //Zend_Db_Table::ON_UPDATE    => Zend_Db_Table::CASCADE
        )
     );
    
    public function __construct($config = array()) {
        parent::__construct($config);
    }
    
    public function init() {
        parent::init();
    }
}
