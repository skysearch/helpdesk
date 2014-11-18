<?php
class Admin_Forms_Validate_UserExist extends Zend_Validate_Abstract
{
	
	const NOT_AUTHORISED = 'noAuthorised';
	protected $_messageTemplates = array(self::NOT_AUTHORISED=>'Este nome de usuário já está sendo utilizado.');
	
	/* (non-PHPdoc)
     * @see Zend_Validate_Interface::isValid()
     */
    public function isValid ($value,$context=null)
    {
        $value = (string) $value;
        $this->_setValue($value);

        $user = Sky_Model_Factory::getInstance()->setModule('admin')->getUser();
        
    
        if($user->exist('username',$value)) {
            $this->_error(self::NOT_AUTHORISED);
            return false;
        }
        return true;
    }
    
    
    
}
