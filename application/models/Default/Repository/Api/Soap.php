<?php
/**
 * This class decorates Soap service classes, provides and enforces authentication via soap header 'authenticate'
 *
 * @author Karsten Deubert <karsten@deubert.net>
 */
class Application_Model_Default_Repository_Api_Soap
{
    /**
     * @var bool
     */
    protected $_authenticationHeaderPresent = false;

    /**
     * @var mixed
     */
    protected $_authenticatedUser = null;

    /**
     * @var mixed
     */
    protected $_serviceClass = null;

    public function __construct($class)
    {
        if (!class_exists($class))
        {
            throw new Exception('invalid class: '.$class);
        }
        $this->_serviceClass = new $class();
    }

    /**
     * @param mixed $data
     * @return void
     */
    public function authenticate($data)
    {
        $this->_authenticationHeaderPresent = true;

        // authentication code which checks if credentials are valid

        $this->_authenticatedUser = $yourAuthenticatedUser;
    }

    public function __call($name, $arguments)
    {
        if (!$this->isAuthenticationHeaderPresent() || is_null($this->_authenticatedUser))
        {
            throw new Exception('authentication failed');
        }
        if (!is_callable(array($this->_serviceClass, $name)))
        {
            throw new Exception('invalid service class method');
        }

        return call_user_func_array(array($this->_serviceClass, $name), $arguments);
    }
}