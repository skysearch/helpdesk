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
class Application_Model_Default_Table_Mail_History extends Sky_Db_Table_Abstract {
    //put your code here
    protected $_name = 'mail_history';
    protected $_primary = array('history_id');

    protected $_autoDate = true;
}


