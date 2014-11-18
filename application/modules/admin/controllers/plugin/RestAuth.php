<?php

class Admin_Controllers_Plugin_RestAuth extends Zend_Controller_Plugin_Abstract {

    /*public function preDispatch(Zend_Controller_Request_Abstract $request) {
        if ($request->getModuleName() != 'default') {
            return false;
        }
        
        //$configs = Sky_Module_Config::getConfig('default', 'rest')->toArray();
        $configs = Sky_Module_Config::getTable('api');

        $error = false;
        
        $controllers = explode(',',$configs['api_controllers']);
        if (in_array($request->getControllerName(), $controllers)) {
            
                $apiKey = $request->getHeader('apikey');
                if ($apiKey != md5($configs['api_tokien'])) {
                    $error = true;
                    $msg = "Invalid API Key";
                }
                
                $ips = explode(',',$configs['api_ip']);
                if(!in_array($request->getClientIp(),$ips)) {
                    $error = true;
                    $msg = "IP não autorizado.";
                }
                
                
                if($error) {
                    $this->getResponse()
                            ->setHttpResponseCode(403)
                            ->appendBody("<error><alert>API Error</alert><value>{$msg}</value></error>")
                            ->setHeader('Content-Type','text/xml');

                    $request->setModuleName('default')
                            ->setControllerName('error')
                            ->setActionName('access')
                            ->setDispatched(true);
                }
        } else {
            $this->getResponse()
                            ->setHttpResponseCode(403)
                            ->appendBody("<error><alert>API Error</alert><value>Controller não faz parte da API</value></error>")
                            ->setHeader('Content-Type','text/xml');
        }
        
    }*/

}