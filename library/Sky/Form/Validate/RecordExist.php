<?php

class Sky_Form_Validate_RecordExist extends Zend_Validate_Abstract {

    protected $_repository;
    protected $_function;
    
    const RECORD_EXISTS = 'recordExists';

    protected $_messageTemplates = array(
	  self::RECORD_EXISTS => 'O valor %value% já existe na base de dados!'
	);

    public function __construct($module,$repository,$function)
	{
                $this->_module = $module;
                $this->_repository = $repository;
                $this->_function = $function;
	} 

    public function isValid($value, $context = null) {
        $value = (string) $value;
        $this->_setValue($value);

        $rName = 'get'.$this->_repository;
        $repository = Sky_Model_Factory::getInstance()->setModule($this->_module)->$rName();
        
        
        $fName = $this->_function;
        
        
        if ($repository->$fName($value)) {
            $this->_error(self::RECORD_EXISTS);
            return false;
        }
        return true;
    }

}
