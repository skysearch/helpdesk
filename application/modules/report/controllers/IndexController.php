<?php

class Report_IndexController extends Zend_Controller_Action
{
    protected $_auth;
    protected $_info;
    protected $_configs;
    protected $_url;
    protected $_pass;

    public function init()
    {
       $this->_auth = Zend_Auth::getInstance();
       $this->_info = $this->_auth->getIdentity();
       $this->_configs = Sky_Module_Config::getTable('report');
       
       $this->_pass = date('d').sha1($this->_info['info']['email']).rand(10,99);
    }

    public function indexAction()
    {    
       $this->_url = "{$this->_configs['report_url']}?u={$this->_info['info']['email']}&p={$this->_pass}"; 
       $this->view->assign('url', $this->_url);
    }
    
    public function adminAction()
    {
       $this->_url = "{$this->_configs['admin_url']}?u={$this->_info['info']['email']}&p={$this->_pass}";
       $this->view->assign('url', $this->_url);
       $this->render('index');
    }

}

