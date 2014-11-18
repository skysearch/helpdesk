<?php
require_once APPLICATION_PATH.DS.'modules'.DS.'varejo'.DS.'controllers'.DS.'EdiController.php';


class EdiController extends Varejo_EdiController {

    public function init() {
        
        Zend_Layout::getMvcInstance()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        
        parent::init();
    }

    public function indexAction() {
        $this->_helper->redirector->goToRoute(array('action' => 'login', 'controller' => 'auth', 'module' => 'admin'), null, true);
    }

}