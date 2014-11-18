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
class Application_Model_Default_Table_Admin_Categoria extends Sky_Db_Table_Abstract {
    //put your code here
    protected $_name = 'core_categoria';
    protected $_primary = array('categoria_id');
    protected $_dependentTables = array('Application_Model_Default_Table_Admin_Categoria');
    protected $_referenceMap     = array(
        'Child' => array(
            'columns'                   => array('name'),
            'refTableClass'             => 'Application_Model_Default_Table_Admin_Categoria',
            'refColumns'                => array('parent'),
        )
     );

    protected $_dataType = array(
        'name' => array('type'=>'slug'),
        'parent' => array('type'=>'slug'),
    );
    
    protected $_autoDate = false;
    
    
    
}

