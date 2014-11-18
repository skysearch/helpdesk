<?php
require_once APPLICATION_PATH.DS.'modules'.DS.'helpdesk'.DS.'controllers'.DS.'TicketController.php';


class CronTicketController extends Helpdesk_TicketController {

    public function init() {
        
        Zend_Layout::getMvcInstance()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        
        parent::init();
    }

    public function indexAction() {
        $this->_helper->redirector->goToRoute(array('action' => 'login', 'controller' => 'auth', 'module' => 'admin'), null, true);
    }
   
}