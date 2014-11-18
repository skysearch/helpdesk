<?php

class CronController extends Zend_Controller_Action {

    public function init() {
        Zend_Layout::getMvcInstance()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
    }

    public function indexAction() {
        $this->_helper->redirector->goToRoute(array('action' => 'login', 'controller' => 'auth', 'module' => 'admin'), null, true);
    }

    public function getAction() {
        $backup = new Sky_Backup();
        $backup->setType('Table');
        $backup->save();
    }
    
    

}

