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
class Application_Model_Default_Table_Api_CepCidade extends Sky_Db_Table_Abstract {

    //put your code here
    protected $_name = 'api_cep_cidade';
    protected $_primary = array('cidade_id');
    protected $_dependentTables = array('Application_Model_Default_Table_Api_CepEndereco','Application_Model_Default_Table_Api_CepBairro');

    protected $_autoDate = false;

}

