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
class Application_Model_Default_Table_Helpdesk_Andamento extends Sky_Db_Table_Abstract {
    //put your code here
    protected $_name = 'tic_andamento';
    protected $_primary = array('andamento_id');
    //protected $_dependentTables = array('Application_Model_Default_Table_Admin_User');
    
    protected $_referenceMap     = array(
        'Ticket' => array(
            'columns'                   => array('ticket_id'),
            'refTableClass'             => 'Application_Model_Default_Table_Helpdesk_Ticket',
            'refColumns'                => array('ticket_id'),
            'onDelete'                  => parent::CASCADE,
            'onUpdate'                  => parent::RESTRICT
            
        ),
        'User' => array(
            'columns'                   => array('user_id'),
            'refTableClass'             => 'Application_Model_Default_Table_Admin_User',
            'refColumns'                => array('user_id'),
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


