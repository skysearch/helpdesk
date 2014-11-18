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
class Application_Model_Default_Table_Api_CepBairro extends Sky_Db_Table_Abstract {

    //put your code here
    protected $_name = 'api_cep_bairro';
    protected $_primary = array('bairro_id');
    protected $_dependentTables = array('Application_Model_Default_Table_Api_CepEndereco');
    protected $_referenceMap = array(
        'Cidade' => array(
            'columns' => array('cidade_id'),
            'refTableClass' => 'Application_Model_Table_Api_CepCidade',
            'refColumns' => array('cidade_id'),
        //Zend_Db_Table::ON_DELETE    => Zend_Db_Table::CASCADE,
        //Zend_Db_Table::ON_UPDATE    => Zend_Db_Table::CASCADE
        )
    );
    
    protected $_autoDate = false;

}

