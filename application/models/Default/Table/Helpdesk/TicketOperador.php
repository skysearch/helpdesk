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
class Application_Model_Default_Table_Helpdesk_TicketOperador extends Sky_Db_Table_Abstract {
    //put your code here
    protected $_name = 'tic_ticket_operador';
    protected $_primary = array('ticket_id','operador_id');
    //protected $_dependentTables = array('Application_Model_Default_Table_Admin_User');
    
    protected $_referenceMap     = array(
        'Ticket' => array(
            'columns'                   => array('ticket_id'),
            'refTableClass'             => 'Application_Model_Default_Table_Helpdesk_Ticket',
            'refColumns'                => array('ticket_id'),
        ),
        'Operador' => array(
            'columns'                   => array('operador_id'),
            'refTableClass'             => 'Application_Model_Default_Table_Helpdesk_Operador',
            'refColumns'                => array('operador_id'),
        )
    );
    
    protected $_dataType = array(
        //'status' => array('type'=>'bool'),
    );
    
    //protected $_autoDate = true;
}


