<?php

/**
 * @copyright	Copyright (c) 2009-2010 TIG Corporation (http://www.tig.vn)
 * @license		http://www.gnu.org/licenses/gpl-2.0.txt GNU GENERAL PUBLIC LICENSE Version 2
 * @since		2.0.0
 */
class Api_Services_Auth implements Zend_Auth_Adapter_Interface {
    /**
     * Authenticated success
     * Its value must be greater than 0
     */

    const SUCCESS = 1;

    /**
     * Constant define that user has not been active
     * Its value must be smaller than 0
     */
    const NOT_ACTIVE = -1;

    /**
     * General failure
     * Its value must be smaller than 0
     */
    const FAILURE = -2;

    private $_username;
    private $_password;

    public function __construct($username, $password) {
        $this->_username = $username;
        $this->_password = $password;
    }

    /**
     * Performs an authentication attempt
     *
     * @throws Zend_Auth_Adapter_Exception If authentication cannot be performed
     * @return Zend_Auth_Result
     */
    public function authenticate() {

        //$conn = Sky_Db_Connection::getInstance()->connect();
        $user = Sky_Model_Factory::getInstance()->setModule('api')->getUser();

        $user = $user->authenticate($this->_username, $this->_password);
        
        if (null == $user) {
            return new Zend_Auth_Result(self::FAILURE, null);
        }
        if (!(bool)$user['user']['status']) {
            return new Zend_Auth_Result(self::NOT_ACTIVE, null);
        }
        
        return new Zend_Auth_Result(self::SUCCESS, $user);
    }

}
