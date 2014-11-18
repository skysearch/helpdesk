<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Admin_Forms_Validate_PasswordConfirmation extends Zend_Validate_Abstract
{
    const NOT_MATCH = 'notMatch';
 
    protected $_messageTemplates = array(
        self::NOT_MATCH => 'Senha atual inválida.'
    );
 
    public function isValid($value, $context = null)
    {
        $value = (string) $value;
        $this->_setValue($value);
 
        $user = Sky_Model_Factory::getInstance()->setModule('admin')->getUser();
        
        $auth = Zend_Auth::getInstance();
        if(!($auth->hasIdentity()))
            return false;
        
        $info = $auth->getIdentity();
        
        $row = $user->getById($info['user']['user_id']);
        if(count($row)<=0)
            return false;
        
        if($user->exist('username', $row['username'], $value)) {
            return true;
        }
        
        $this->_error(self::NOT_MATCH);
        return false;
    }
}
?>
