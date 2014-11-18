<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Admin_Controllers_Plugin_Log extends Zend_Controller_Plugin_Abstract {


    protected $_clientIp;
    protected $_requestUri;
    protected $_config;


    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        $this->_config = Sky_Config::getConfig();

        if (!($this->_config->log->user))
            return true;


        $message = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');

        $auth = Zend_Auth::getInstance();
        $this->_clientIp = $request->getClientIp();
        $this->_requestUri = $request->getRequestUri();
        
        $data = array(
            'request' => $this->_requestUri,
            'user_name' => '',
            'data_post' => '',
            'level' => 'INFO',
            'event' => '',
            'type' => Sky_Log::TYPE_USER,
            'ip' => $this->_clientIp,
            'session_id' => Zend_Session::getId()
        );

        if ($auth->hasIdentity()) {
            $user = Zend_Auth::getInstance()->getIdentity();
            $data['user_name'] = $user['info']['name'];
        }

        if ($request->isPost() === true) {
            $data['data_post'] = $request->getPost();
        }


        if ($message->hasMessages())
            $data['event'] = $message->getMessages();


        $log = Sky_Model_Factory::getInstance()->setModule('admin')->getLog();
        $log->setUser($data);
    }

    /*
    public function routeStartup(Zend_Controller_Request_Abstract $request) {

        $errors = $request->getParam('error_handler');

        if (empty($errors))
            return true;


        $auth = Zend_Auth::getInstance();
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error -- controller or action not found
                $priority = 'NOTICE';
                $message = 'Page not found';
                break;
            default:
                // application error
                $priority = 'CRIT';
                $message = 'Application error';
                break;
        }

        $data = array(
            'request' => $errors->request->getParams(),
            'user_name' => '',
            'data_post' => $errors->exception->getMessage(),
            'level' => $priority,
            'event' => $message,
            'type' => Sky_Log::TYPE_ERROR,
            'ip' => $request->getClientIp(),
            'session_id' => Zend_Session::getId()
        );

        if ($auth->hasIdentity()) {
            $user = Zend_Auth::getInstance()->getIdentity();
            $data['user_name'] = $user['info']['name'];
        }


        $data['data_post'] = $errors->exception->getMessage();

        $log = Sky_Model_Factory::getInstance()->getLog();
        $log->setLog($data);
    }
    */
    public function dispatchLoopShutdown() {
        
        $url = explode('/',$this->_requestUri);
        
        if (!($this->_config->log->db) || !($this->_config->resources->db->params->profiler) || in_array('log',$url))
            return true;

        $log = Sky_Model_Factory::getInstance()->setModule('admin')->getLog();
        $db = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('db');
        $profiler = $db->getProfiler();
        $profiler->setFilterElapsedSecs(null);
        $profiler->setFilterQueryType(null);
        $totalTime = number_format($profiler->getTotalElapsedSecs()* 1000,3);
        $queryCount = $profiler->getTotalNumQueries();
        $longestTime = 0;
        $longestQuery = null;
        $querys = array();


        if ($queryCount > 0) {
            $auth = Zend_Auth::getInstance();

            foreach ($profiler->getQueryProfiles() as $query) {
                if ($query->getElapsedSecs() > $longestTime) {
                    $longestTime = $query->getElapsedSecs();
                    $longestQuery = $query->getQuery();
                }

                $ms = $query->getElapsedSecs() * 1000;
                $querys[] = array('query' => $query->getQuery(), 'secs' => number_format($ms, 3), 'params' => $query->getQueryParams());
            }

            $level = "DEBUG";
            
            if($totalTime>50)
                $level = "ALERT";
            
            else if($totalTime>100)
                $level = "CRIT";
            
            

            $data = array(
                'request' => $this->_requestUri,
                'user_name' => '',
                'data_post' => array('querys' => $querys, 'longestQuery' => $longestQuery, 'longestTime' => number_format($longestTime*1000,3), 'totalTime' => $totalTime, 'totalQuery' => $queryCount),
                'level' => $level,
                'event' => 'Execultado ' . $queryCount . ' queries em ' . $totalTime . ' ms',
                'type' => Sky_Log::TYPE_DB,
                'ip' => $this->_clientIp,
                'session_id' => Zend_Session::getId()
            );

            if ($auth->hasIdentity()) {
                $user = Zend_Auth::getInstance()->getIdentity();
                $data['user_name'] = $user['info']['name'];
            }

            $log->setDb($data);
        }
    }
}

