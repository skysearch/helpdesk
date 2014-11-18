<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Abstract
 *
 * @author Renato David
 */
class Sky_Db_Table_Row extends Sky_Db_Table_Row_Abstract {

    public function __wakeup(){
            // tbl comes back as a string
            $tbl = $this->getTableClass();

            // dynamically instantiate an instance of the table
            $this->setTable(new $tbl);
            
            $this->_connected = true;
    }
    
    
    /**
     * Constructor.
     *
     * Supported params for $config are:-
     * - table       = class name or object of type Zend_Db_Table_Abstract
     * - data        = values of columns in this row.
     *
     * @param  array $config OPTIONAL Array of user-specified config options.
     * @return void
     * @throws Zend_Db_Table_Row_Exception
     */
    public function __construct(array $config = array()) {
        parent::__construct($config);
    }

}

