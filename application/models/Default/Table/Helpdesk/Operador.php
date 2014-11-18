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
class Application_Model_Default_Table_Helpdesk_Operador extends Sky_Db_Table_Abstract {
    //put your code here
    protected $_name = 'tic_operador';
    protected $_primary = array('operador_id');
    protected $_dependentTables = array('Application_Model_Default_Table_Helpdesk_Andamento',
                                        'Application_Model_Default_Table_Helpdesk_Ticket',
                                        'Application_Model_Default_Table_Helpdesk_TicketOperador');
    
    protected $_referenceMap     = array(
        'User' => array(
            'columns'                   => array('operador_id'),
            'refTableClass'             => 'Application_Model_Default_Table_Admin_User',
            'refColumns'                => array('user_id'),
        )
    );
    
    protected $_dataType = array(
        'status' => array('type'=>'bool'),
    );
    
    //protected $_autoDate = true;
}


