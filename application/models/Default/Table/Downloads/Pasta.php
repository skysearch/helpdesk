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
class Application_Model_Default_Table_Downloads_Pasta extends Sky_Db_Table_Abstract {
    //put your code here
    protected $_name = 'down_pasta';
    protected $_primary = array('pasta_id');
    protected $_dependentTables = array('Application_Model_Default_Table_Downloads_Arquivo');
    
    protected $_referenceMap     = array(
        'User' => array(
            'columns'                   => array('user_id'),
            'refTableClass'             => 'Application_Model_Default_Table_Admin_User',
            'refColumns'                => array('user_id'),
        ),
        'Departamento' => array(
            'columns'                   => array('departamento_id'),
            'refTableClass'             => 'Application_Model_Default_Table_Downloads_Departamento',
            'refColumns'                => array('departamento_id'),
        )
    );
    
    protected $_dataType = array(
        'created' => array('type'=>'datetime'),
        'modified' => array('type'=>'datetime'),
        'arquivo' => array('type'=>'array'),
        'grupo' => array('type'=>'array')
    );
    
    protected $_autoDate = true;
}


