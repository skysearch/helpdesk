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
class Application_Model_Default_Table_Admin_Privilege extends Sky_Db_Table_Abstract {
    //put your code here
    protected $_name = 'core_privilege';
    protected $_primary = array('privilege_id');
    protected $_dependentTables = array('Application_Model_Default_Table_Admin_Rule');
    protected $_referenceMap     = array(
        'Module' => array(
            'columns'                   => array('module_name'),
            'refTableClass'             => 'Application_Model_Table_Admin_Module',
            'refColumns'                => array('name'),
            //Zend_Db_Table::ON_DELETE    => Zend_Db_Table::CASCADE,
            //Zend_Db_Table::ON_UPDATE    => Zend_Db_Table::CASCADE
        ),
        'Controller' => array(
            'columns'                   => array('controller_name'),
            'refTableClass'             => 'Application_Model_Table_Admin_Resource',
            'refColumns'                => array('controller_name'),
            //Zend_Db_Table::ON_DELETE    => Zend_Db_Table::CASCADE,
            //Zend_Db_Table::ON_UPDATE    => Zend_Db_Table::CASCADE
        )
     );
    
    protected $_dataType = array(
        'is_visible' => array('type'=>'bool'),
    );
    
    protected $_autoDate = true;
}
