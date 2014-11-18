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
class Application_Model_Default_Table_Admin_IntegrityHashes extends Sky_Db_Table_Abstract {
    //put your code here
    protected $_name = 'core_integrity_hashes';
    protected $_primary = array('file_path');
    protected $_isCachable = false;
    protected $_autoDate = false;
}


