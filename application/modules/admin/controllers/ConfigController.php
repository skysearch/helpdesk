<?php

class Admin_ConfigController extends Zend_Controller_Action
{

    public function init()
    {
       
    }

    public function indexAction()
    {
        $this->view->assign('title','Gerenciamento de Configurações');
        $this->render('index');
    }

    
    public function editarAction(){

        $form = new Admin_Forms_Config();
        $form->jqueryValidate(true);
        
        $config = Sky_Model_Factory::getInstance()->setModule('admin')->getConfig();

        if($this->getRequest()->isPost()){
            $post = $this->getRequest()->getPost();

            if($form->isValid($post)){
                $post = $form->getValues();
                $data =array();

                foreach ($post as $k=>$v) {
                    if(empty($v))                        
                        continue;

                    $data[$k] = $v;
                }

                if($config->update($data)){
                    $this->_helper->getHelper('FlashMessenger')
                            ->addMessage('Configurações editadas com sucesso.','sucess');
                    $this->_helper->redirector->goToRoute(array('action'=>'editar','controller'=>'config','module'=>'admin'),null,true);
                } 
            } else {
                $form->populate($data);
            }
        }
        
        $this->view->assign('form',$form);
        $this->view->assign('title','Configurações');
        
    }
    
    public function infoAction(){
       
        $cliente = array(
            //array('label'=>'Página Origem','value'=>$_SERVER['HTTP_REFERER']),
            array('label'=>'Navegador','value'=>$_SERVER['HTTP_USER_AGENT']),
            array('label'=>'IP','value'=>$_SERVER['REMOTE_ADDR']),
        );
        
        $servidor = array(
            array('label'=>'Servidor','value'=>$_SERVER['SERVER_NAME']),
            array('label'=>'Data/Hora','value'=>  date('d/m/Y H:i:s')),
            array('label'=>'IP','value'=>$_SERVER['SERVER_ADDR']),
            array('label'=>'PHP','value'=>phpversion()),
        );
        
        $database = Sky_Db_Connection::getInstance()->connect();
        $version = $database->getServerVersion();
        $config = $database->getConfig();
        $configs = Sky_Config::getConfig()->toArray();

        $db = array(
            array('label'=>'Adapter','value'=>$configs['resources']['db']['adapter']),
            array('label'=>'Version','value'=>$version),
            array('label'=>'Host','value'=>$config['host']),
            array('label'=>'Port','value'=>$config['port']),
            array('label'=>'Db','value'=>$config['dbname']),
            array('label'=>'Charset','value'=>$config['charset']),
            array('label'=>'Persist','value'=>$config['persistent']),
        );
        
        
        
        $aparencia = array(
            array('label'=>'Skin','value'=>$configs['web']['backend']['skin']),
            array('label'=>'Layout','value'=>$configs['web']['backend']['layout']),
            array('label'=>'Locale','value'=>key(Zend_Locale::getDefault())),
        );
        
        $cookie = array(
            array('label'=>'Remember me Seconds','value'=>$configs['resources']['session']['remember_me_seconds']),
            array('label'=>'Cookie Lifetime','value'=>$configs['resources']['session']['cookie_lifetime']),
        );
        
        $cache = array(
            array('label'=>'Caching','value'=>(int)$configs['cache']['caching']),
            array('label'=>'Caching Lifetime','value'=>$configs['cache']['frontend']['options']['lifetime']),
        );
        
        $logs = array(
            array('label'=>'User','value'=>(int)$configs['log']['user']),
            array('label'=>'System','value'=>(int)$configs['log']['system']),
            array('label'=>'Db','value'=>(int)$configs['log']['db']),
            array('label'=>'Url','value'=>$configs['log']['url']),
            array('label'=>'Post','value'=>$configs['log']['post']),
        );
        
        $bootstrap = $this->getInvokeArg('bootstrap');
        $userAgent = $bootstrap->getResource('useragent');
        $userAgent->getDevice();
                
        //Zend_Debug::dump($userAgent->getBrowserType());
        
        /*$agente = array(
            array('label'=>'User Agente','value'=>$userAgent),
            array('label'=>'User Device','value'=>$userDevice),
        );*/
        
        $informations = array("Cliente"=>$cliente,
                              "Servidor"=>$servidor,
                              "Banco de Dados"=>$db,
                              "Aparencia"=>$aparencia,
                              "Cookie"=>$cookie,
                              "Cache"=>$cache,
                              "Logs"=>$logs,
                              //"Agente"=>$userAgent->getDevice()->getAllFeatures()
        );
        
        //Zend_Debug::dump(key(Zend_Locale::getDefault()));
        
        $this->view->assign('informations',$informations);
        $this->view->assign('title','Informações do Sistema');
        
    }
}

