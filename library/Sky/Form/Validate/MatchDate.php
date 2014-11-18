<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Sky_Form_Validate_MatchDate extends Zend_Validate_Abstract {
   
    /**
     * Error codes
     * @const string
     */
    const MATCHDATE_OVER = 'machdateOver';
    const MATCHDATE_LESS = 'machdateLess';
    const MATCHDATE_EQUAL = 'machdateEqual';

    
    /*
     * Operators
     * 
     */
    const M_OVER = 'over';
    const M_LESS = 'less';
    const M_EQUAL = 'equal';
    
    /**
     * Error messages
     * @var array
     */
    protected $_messageTemplates = array(
        self::MATCHDATE_OVER => "'%value%' deve ser maior que '%key_match_date_error%'",
        self::MATCHDATE_LESS => "'%value%' deev ser menor que '%key_match_date_error%'",
        self::MATCHDATE_EQUAL => "'%value%' é diferente de '%key_match_date_error%'",
    );

    
    protected $_operator;
    protected $_token;

   /**
     * Sets validator options
     *
     * @param  mixed $token
     * @return void
     */
    public function __construct($token = null,$operator = null) {
        $this->_token = $token;
        $this->_operator = $operator;
    }

    /**
     * Retrieve token
     *
     * @return string
     */
    public function getToken() {
        return $this->_token;
    }

    /**
     * Set token against which to compare
     *
     * @param  mixed $token
     * @return Zend_Validate_Identical
     */
    public function setToken($token) {
        $this->_token = $token;
        return $this;
    }


    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if and only if a token has been set and the provided value
     * matches that token.
     *
     * @param  mixed $value
     * @param  array $context
     * @return boolean
     */
    public function isValid($value, $context = null) {
        $this->_setValue((string) $value);

        $date = $this->_prepareDate($value);
        $compare = $this->_prepareDate($context[$this->_token]);
        
        switch($this->_operator)
        {
            case self::M_OVER:
                if($date->get('U')<$compare->get('U')){
                    $this->_setFormatMessage(self::MATCHDATE_OVER,$compare->get(Zend_Date::DATE_MEDIUM));
                    $this->_error (self::MATCHDATE_OVER);
                    return false;
                }
                break;
            
            case self::M_LESS:
                if($date->get('U')>$compare->get('U')) {
                    $this->_setFormatMessage(self::MATCHDATE_LESS,$compare->get(Zend_Date::DATE_MEDIUM));
                    $this->_error (self::MATCHDATE_LESS);
                    return false;
                }
                break;
            
            case self::M_EQUAL:    
                if($date->get('U')!=$compare->get('U')) {
                    $this->_setFormatMessage(self::MATCHDATE_EQUAL,$compare->get(Zend_Date::DATE_MEDIUM));
                    $this->_error (self::MATCHDATE_EQUAL);
                    return false;
                }
                break;
        }

        return true;
    }
    
    
    protected function _setFormatMessage($key,$value){
        $this->_messageTemplates[$key] = str_replace('%key_match_date_error%', $value, $this->_messageTemplates[$key]);
    }





    protected function _prepareDate($date) {
        $date = str_replace('/', '-', $date);
        
        if(Zend_Date::isDate($date)) {
            $date = new Zend_Date($date);
        } else {
            return null;
        }
        
        return $date;
    }
}

