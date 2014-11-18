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
class Application_Model_Default_Table_Helpdesk_Departamento extends Sky_Db_Table_Abstract {
    //put your code here
    protected $_name = 'tic_departamento';
    protected $_primary = array('departamento_id');
    protected $_dependentTables = array('Application_Model_Default_Table_Helpdesk_Ticket');
    
    protected $_referenceMap     = array(
        'Role' => array(
            'columns'                   => array('departamento_id'),
            'refTableClass'             => 'Application_Model_Default_Table_Admin_Role',
            'refColumns'                => array('role_id'),
        )
    );
    
    protected $_dataType = array(
        'status' => array('type'=>'bool'),
    );
    
    //protected $_autoDate = true;
}


