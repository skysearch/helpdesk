<?php

/**
 * Flash Messenger - implement session-based messages
 *
 * @uses       Zend_Controller_Action_Helper_FlashMessenger
 * @category   Pyc
 * @package    PYC_Controller
 * @subpackage PYC_Constellation_Controller_Action_Helper
 * @version    $Id: FlashMessenger.php 23775 2011-03-01 17:25:24Z ralph $
 */
class Sky_Controller_Action_Helper_FlashMessenger
      extends Zend_Controller_Action_Helper_FlashMessenger
{
    /**
     * $_namespace - Instance namespace, default is 'default'
     *
     * @var string
     */
    protected $_namespace = 'SKY_MENSSAGE';


    /**
     * __construct() - Instance constructor, needed to get iterators, etc
     *
     * @param  string $namespace
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * setNamespace() - change the namespace messages are added to, useful for
     * per action controller messaging between requests
     *
     * @param  string $namespace
     * @return Zend_Controller_Action_Helper_FlashMessenger Provides a fluent interface
     */
    public function setNamespace($namespace = 'SKY_MENSSAGE')
    {
        $this->_namespace = $namespace;
        return $this;
    }

    /**
     * addMessage() - Add a message to flash message
     *
     * @param  string $message
     * @return Zend_Controller_Action_Helper_FlashMessenger Provides a fluent interface
     */
    public function addMessage($message,$type)
    {
        if (self::$_messageAdded === false) {
            self::$_session->setExpirationHops(1, null, true);
        }

        if (!is_array(self::$_session->{$this->_namespace})) {
            self::$_session->{$this->_namespace} = array();
        }

        self::$_session->{$this->_namespace}[$type][] = $message;

        return $this;
    }

    /**
     * getMessages() - Get messages from a specific namespace
     *
     * @return array
     */
    public function getMessages()
    {
        if ($this->hasMessages()) {
            return self::$_messages[$this->_namespace];
        }

        return array();
    }
    
    /**
     * getMessages() - Get messages from a specific namespace
     * in format html
     *
     * @return array
     */
    public function getHtmlMessages()
    {
        if ($this->hasMessages()) {
            $html = '';
            foreach (parent::getMessages() as $type=>$message){
                $html .= '<ul class="message ' . $type . '">';
                foreach ($message as $msg){
                    $html .= '<li>'.$msg.'</li>';
                }
                $html .= '</ul>';
            };
        }

        return $html;
    }

    /**
     * count() - Complete the countable interface
     *
     * @return int
     */
    public function count()
    {
        if ($this->hasMessages()) {
            $count =0;
            foreach (parent::getMessages() as $messages){
                $count += count($messages);
            }
            return $count;
        }
        return 0;
    }

    /**
     * Strategy pattern: proxy to addMessage()
     *
     * @param  string $message
     * @return void
     */
    public function direct($message,$type)
    {
        return $this->addMessage($message,$type);
    }
}
