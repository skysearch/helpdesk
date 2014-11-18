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
class Application_Model_Default_Table_Mail_Template extends Sky_Db_Table_Abstract {
    //put your code here
    protected $_name = 'mail_template';
    protected $_primary = array('template_id');
    
    protected $_dataType = array(
        'name' => array('type'=>'slug'),
    );
    
    protected $_autoDate = true;
}


