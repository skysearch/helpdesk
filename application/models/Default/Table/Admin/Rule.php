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
class Application_Model_Default_Table_Admin_Rule extends Sky_Db_Table_Abstract {

    //put your code here
    protected $_name = 'core_rule';
    protected $_primary = array('rule_id');
    protected $_referenceMap = array(
        'Privilege' => array(
            'columns' => array('privilege_id'),
            'refTableClass' => 'Application_Model_Table_Admin_Privilege',
            'refColumns' => array('privilege_id'),
        //Zend_Db_Table::ON_DELETE    => Zend_Db_Table::CASCADE,
        //Zend_Db_Table::ON_UPDATE    => Zend_Db_Table::CASCADE
        ),
        'Role' => array(
            'columns' => array('role_id'),
            'refTableClass' => 'Application_Model_Table_Admin_Role',
            'refColumns' => array('role_id'),
        //Zend_Db_Table::ON_DELETE    => Zend_Db_Table::CASCADE,
        //Zend_Db_Table::ON_UPDATE    => Zend_Db_Table::CASCADE
        )
    );
    
    protected $_autoDate = true;

}

