<?php

class Admin_ErrorController extends Zend_Controller_Action
{
    
    public function init() {

    }

    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');
        
        if (!$errors || !$errors instanceof ArrayObject) {
            $message = 'You have reached the error page';
            return;
        }
        
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $priority = 'ALERT';
                $message = 'Page not found';
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $priority = 'CRIT';
                $message = 'Application error';
                break;
        }
        
        
        $auth = Zend_Auth::getInstance();
        $request = $this->getRequest();
        
        
       
        $data = array(
            'request' => $request->getRequestUri(),
            'user_name' => '',
            'data_post' => $errors->exception,
            'level' => $priority,
            'event' => $errors->exception->getMessage(),
            'type' => Sky_Log::TYPE_ERROR,
            'ip' => $request->getClientIp(),
            'session_id' => Zend_Session::getId()
        );

        if ($auth->hasIdentity()) {
            $user = Zend_Auth::getInstance()->getIdentity();
            $data['user_name'] = $user['info']['name'];
        }

        

        
        $log = Sky_Model_Factory::getInstance()->setModule('admin')->getLog();
        $id = $log->setError($data);
        
        $row = $log->getById($id);
        $configs = Sky_Module_Config::getTable('error');
        $destinatario = $configs['error_mail'];
        
        $row['log']['data_post'] = nl2br($row['log']['data_post']);
        $row['log']['created'] = ($row['log']['created'] instanceof DateTime)?$row['log']['created']->format('d/m/Y H:i:s'):$row['log']['created'];
        
        $mail = new Sky_Mail();
        $mail->setTemplate($row['log'],'error');
        $mail->send("{$_SERVER['HTTP_HOST']} - {$message}",null,$destinatario);
        
        
        $this->view->message = $message;
        $this->view->request = $errors->request;
    }

}

