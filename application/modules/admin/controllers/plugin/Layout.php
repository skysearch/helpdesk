<?php
/**
 * @copyright	Copyright (c) 2009-2010 TIG Corporation (http://www.tig.vn)
 * @license		http://www.gnu.org/licenses/gpl-2.0.txt GNU GENERAL PUBLIC LICENSE Version 2
 * @version 	$Id: ActionCache.php 5451 2010-09-16 08:23:32Z huuphuoc $
 * @since		2.0.0
 */

class Admin_Controllers_Plugin_Layout extends Zend_Controller_Plugin_Abstract 
{
    
    protected $_view;
    
	public function preDispatch(Zend_Controller_Request_Abstract $request) 
	{
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
		if (null === $viewRenderer->view) {
			$viewRenderer->initView();
		}
		$view = $viewRenderer->view;
                
                
               
                
                
                /*
                 * Load head
                 */
                $module = $request->getModuleName();
                $controller = $request->getControllerName();
                
                $config = Sky_Config::getConfig();

                $skin = "{$config->web->url->static}/themes/frontend/{$config->web->frontend->skin}";
                
                if($module !== 'default') {
                    $skin = "{$config->web->url->static}/themes/admin/{$config->web->backend->skin}";
                }
               
                
                
		$controller_config = Sky_Module_Config::getConfig($module, $controller);
                $module_config = Sky_Module_Config::getConfig($module);
                
                
                $view->getHelper('BaseUrl')->setBaseUrl($config->web->url->static);
                $view->placeholder('base')->set("<base href=\"{$config->web->url->static}\" target=\"_self\" />");
                $view->headMeta()->appendName('viewport',$config->web->viewport);
                
                if(!is_null($module_config)) {
 
                    $doctypeHelper = new Zend_View_Helper_Doctype();
                    $doctypeHelper->doctype($module_config->viewrender->view->doctype);
              
                    if ($view->doctype()->isHtml5()) {
                        $view->headMeta()->setCharset($module_config->viewrender->view->charset);
                    }
                    else {
                        $view->setEncoding($module_config->viewrender->view->encoding);
                    }
                }
                
                $view->skin = $skin;
                        
                $messages = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
                $view->messages = ($messages->count()>0)?$messages->getHtmlMessages():'';
                
                
                if(!is_null($controller_config))
                {
                    
                    $head = $controller_config->toArray();
                    $loads = (key_exists('head', $head))?$head['head']:array('head'=>array());
                    
                    
                    foreach($loads as $key=>$value){ //css || js
                        
                        foreach($value as $key_1=>$value_1){ //cond || all
                            
                            if($key_1=='cond'){
                                foreach ($value_1 as $key_3=>$value_3){ //8 || 9;
                                    
                                    foreach($value_3 as $key_4=>$value_4) {// arquivos
                                        $param = array('conditional' => "lt IE {$key_3}");
                                        $path = "$skin/$key/{$value_4}";
                                        
                                        if($key=='css') {
                                            $view->headLink()->appendStylesheet($path,$param);
                                        } else if($key=='js') {
                                            $view->headScript()->appendFile($path,'text/javascript',$param);
                                        }
                                        
                                        $param = array();
                                    }
                                    
                                }
                            
                            } else if($key_1=='media') { 
                                foreach ($value_1 as $key_3=>$value_3){ //8 || 9;
                                    foreach($value_3 as $key_4=>$value_4) {// arquivos
                                        $conditional = (is_string($key_4))?" " . $key_4:"";
                                        $param = array('media' => $key_3.$conditional);
                                        $path = "$skin/$key/{$value_4}";
                                        $view->headLink()->appendStylesheet($path,$param);
                                        
                                        $param = array();
                                    }
                                }
                            } else {
                          
                                foreach ($value_1 as $key_2=>$value_2){// arquivos
                                    $path = "$skin/$key/{$value_2}";

                                    if($key=='css') {
                                        $view->headLink()->appendStylesheet($path);
                                    } else if($key=='js') {
                                        $view->headScript()->appendFile($path,'text/javascript');
                                    }
                                }
                            }
                        }
                    }

                } 
                
                
                
            $this->_view =  $view ;  
            
	}
        
        public function postDispatch(Zend_Controller_Request_Abstract $request) {
            $config = Sky_Config::getConfig();
            $module = $request->getModuleName();
            
            /*
            * Load Title
            */
            $view = $this->_view;
            
            if(!empty($view->title))
                $view->headTitle($view->title);
            
            if($module === 'default') 
                $view->headTitle($config->web->sitename);
            else 
                $view->headTitle($config->web->sysname);
                
            
            $view->headTitle()->setSeparator(' :: ');
            
            
        }
        
        
        
        /*public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request) {
            $front = Zend_Controller_Front::getInstance();
            if(!$front->hasPlugin('Zend_Controller_Plugin_ActionStack')){
                $actionStack = new Zend_Controller_Plugin_ActionStack();
                $front->registerPlugin($actionStack,97);
            } else {
                $actionStack = $front->getPlugin('Zend_Controller_Plugin_ActionStack');
            }
            
            
            $module = $request->getModuleName(); 
            $controller = $request->getControllerName(); 
            $action = $request->getActionName();
            
            if($module == 'admin' && $controller != 'auth'){
                $boxUser = clone($request);
                $boxUser->setActionName('box-user')
                        ->setControllerName('layout');

                $actionStack->pushStack($boxUser);
            }
        }*/
}
