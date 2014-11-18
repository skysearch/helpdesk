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
class Application_Model_Default_Table_Helpdesk_Ticket extends Sky_Db_Table_Abstract {
    //put your code here
    protected $_name = 'tic_ticket';
    protected $_primary = array('ticket_id');
    protected $_dependentTables = array('Application_Model_Default_Table_Helpdesk_TicketOperador',
                                        'Application_Model_Default_Table_Helpdesk_Andamento');
    
    protected $_referenceMap     = array(
        'User' => array(
            'columns'                   => array('user_id'),
            'refTableClass'             => 'Application_Model_Default_Table_Admin_User',
            'refColumns'                => array('user_id'),
        ),
        'Departamento' => array(
            'columns'                   => array('departamento_id'),
            'refTableClass'             => 'Application_Model_Default_Table_Helpdesk_Departamento',
            'refColumns'                => array('departamento_id'),
        )
    );
    
    protected $_dataType = array(
        'prazo' => array('type'=>'datetime'),
        'created' => array('type'=>'datetime'),
        'modified' => array('type'=>'datetime'),
        'arquivo' => array('type'=>'array'),
    );
    
    protected $_autoDate = true;
}


