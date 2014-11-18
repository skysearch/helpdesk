<?php

class Admin_LogController extends Zend_Controller_Action
{

    public function init()
    {
      
    }

    public function indexAction()
    {
        $this->view->assign('title','Relatórios Administrativos');
    }

    
    public function systemAction(){
        
        $form = new Admin_Forms_SearchDate();
        $form->jqueryValidate(true);
        
        $this->view->assign('title','Relatório de eventos do sistema');
        $log = Sky_Model_Factory::getInstance()->setModule('admin')->getLog();
        
        if($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $rows = $log->getLogs(Sky_Log::TYPE_SYSTEM,$data['data_start'],$data['data_end'],$data['search']);
            
            $form->populate($data);
        } else {
            $rows = $log->getLogs(Sky_Log::TYPE_SYSTEM);
        }
        $this->view->assign('form',$form);
        $this->view->assign('list',$rows);
    }

    public function userAction(){
        $form = new Admin_Forms_SearchDate();
        $form->jqueryValidate(true);
        
        $this->view->assign('title','Relatório de eventos do sistema');
        $log = Sky_Model_Factory::getInstance()->setModule('admin')->getLog();
        
        if($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $rows = $log->getLogs(Sky_Log::TYPE_USER,$data['data_start'],$data['data_end'],$data['search']);
            
            $form->populate($data);
        } else {
            $rows = $log->getLogs(Sky_Log::TYPE_USER);
        }
        $this->view->assign('form',$form);
        $this->view->assign('list',$rows);
    }
    
    
    public function dbAction(){
        $form = new Admin_Forms_SearchDate();
        $form->jqueryValidate(true);
        
        $this->view->assign('title','Relatório de querys do banco de dados');
        $log = Sky_Model_Factory::getInstance()->setModule('admin')->getLog();
        
        if($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $rows = $log->getLogs(Sky_Log::TYPE_DB,$data['data_start'],$data['data_end'],$data['search']);
            
            $form->populate($data);
        } else {
            $rows = $log->getLogs(Sky_Log::TYPE_DB);
        }
        $this->view->assign('form',$form);
        $this->view->assign('list',$rows);
    }
    
    public function errorAction(){
        $form = new Admin_Forms_SearchDate();
        $form->jqueryValidate(true);
        
        $this->view->assign('title','Relatório de erros do sistema');
        $log = Sky_Model_Factory::getInstance()->setModule('admin')->getLog();
        
        if($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $rows = $log->getLogs(Sky_Log::TYPE_ERROR,$data['data_start'],$data['data_end'],$data['search']);
            
            $form->populate($data);
        } else {
            $rows = $log->getLogs(Sky_Log::TYPE_ERROR);
        }
        $this->view->assign('form',$form);
        $this->view->assign('list',$rows);
    }
    
    public function detalheAction(){
         $log = Sky_Model_Factory::getInstance()->setModule('admin')->getLog();
         $detalhe = $log->getById($this->getRequest()->getParam('id'));
         $this->view->assign('title','Detalhes do Log');
         
         $this->view->assign('list',$detalhe);
         //Zend_Debug::dump($detalhe);
    }
    
    public function historySessionAction(){
        Zend_Layout::getMvcInstance()->setLayout('empty');
        
        $id = $this->getRequest()->getParam('id');
        
        $log = Sky_Model_Factory::getInstance()->setModule('admin')->getLog();
        $history = $log->getLogsBySession($id);
        
        $this->view->assign('list',$history);
    }
}

