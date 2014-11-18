<?php

class Admin_BackupController extends Zend_Controller_Action {

    protected $_param;

    public function init() {
        $this->_param = $this->getRequest()->getParam('start', 0);
    }

    public function indexAction() {
        $this->view->assign('title', 'Backup do banco de dados');
    }

    public function completoAction() {
        $backup = new Sky_Backup();
        $backup->setType('Complete');

        if ($this->_param['start'] > 0) {
            $backup->save();

            $this->_helper->getHelper('FlashMessenger')
                    ->addMessage('Backup realizado com sucesso.', 'sucess');
            $this->_helper->redirector->goToRoute(array('action' => 'completo','controller'=>'backup','module'=>'admin'),null,true);
        }
        
        $list = $backup->lists();

        $this->view->assign('list',$list);
        $this->view->assign('title', 'Backup do completo do banco de dados');
    }

    public function tabelaAction() {
        $backup = new Sky_Backup();
        $backup->setType('Table');

        if ($this->_param['start'] > 0) {
            $backup->save();
            
            $this->_helper->getHelper('FlashMessenger')
                    ->addMessage('Backup realizado com sucesso.', 'sucess');
            $this->_helper->redirector->goToRoute(array('action' => 'tabela','controller'=>'backup','module'=>'admin'),null,true);
        }
        
        $list = $backup->lists();
        
        $this->view->assign('list',$list);
        $this->view->assign('title', 'Backup por tabela do banco de dados');
    }

    public function inteligenteAction() {

        $this->view->assign('title', 'Backup inteligente do banco de dados');
    }

}

