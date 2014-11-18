<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Application_Model_Default_Table_Downloads_UserInfo extends Application_Model_Default_Table_Admin_UserInfo {
    
    protected $_dependentTables = array('Application_Model_Default_Table_Downloads_User');
    
    public function __construct($config = array()) {
        parent::__construct($config);
    }
    
    public function init() {
        parent::init();
    }
}
