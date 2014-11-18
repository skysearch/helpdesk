<?php
require_once APPLICATION_PATH . DS  . 
            'modules' . DS . 
            'admin' . DS . 
            'controllers' . DS . 
            'ErrorController.php';

class ErrorController extends Admin_ErrorController
{

    public function init() {
        parent::init();
    }
    
    public function accessAction(){
        Zend_Layout::getMvcInstance()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $data = array(
            'request' => $this->getRequest()->getRequestUri(),
            'user_name' => 'Rest Client',
            'data_post' => $this->getRequest()->getHeader('apikey'),
            'level' => 'ALERT',
            'event' => 'Chave inválida',
            'type' => Sky_Log::TYPE_ERROR,
            'ip' => $this->getRequest()->getClientIp(),
            'session_id' => Zend_Session::getId()
        );

        
        $log = Sky_Model_Factory::getInstance()->setModule('admin')->getLog();
        $log->setError($data);
    }
}

