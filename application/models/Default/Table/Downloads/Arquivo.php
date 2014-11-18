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
class Application_Model_Default_Table_Downloads_Arquivo extends Sky_Db_Table_Abstract {
    //put your code here
    protected $_name = 'down_arquivo';
    protected $_primary = array('arquivo_id');
    //protected $_dependentTables = array('Application_Model_Default_Table_Admin_User');
    
    protected $_referenceMap     = array(
        'Pasta' => array(
            'columns'                   => array('pasta_id'),
            'refTableClass'             => 'Application_Model_Default_Table_Downloads_Pasta',
            'refColumns'                => array('pasta_id'),
            'onDelete'                  => self::CASCADE,
            'onUpdate'                  => self::RESTRICT
        ),
        'User' => array(
            'columns'                   => array('user_id'),
            'refTableClass'             => 'Application_Model_Default_Table_Admin_User',
            'refColumns'                => array('user_id'),
        )
    );
    
    protected $_dataType = array(
        'created' => array('type'=>'datetime'),
        'modified' => array('type'=>'datetime'),
        'arquivo' => array('type'=>'array'),
    );
    
    protected $_autoDate = true;
}


