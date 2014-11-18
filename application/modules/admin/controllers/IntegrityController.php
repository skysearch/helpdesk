<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Admin_IntegrityController extends Zend_Controller_Action
{

    public function init()
    {
        
    }

    public function gerarAction(){
        $integri = new Admin_Services_FileIntegrity();
        $gerar = $integri->buildProfile();
         
        $this->view->assign('title','Gerar lista de arquivos');
        
        $this->_helper->getHelper('FlashMessenger')
                    ->addMessage('Lista de arquivos para comparação gerada com sucesso.', 'sucess');
        
        $this->_helper->redirector->goToRoute(array('action' => 'integridade-files','controller'=>'integrity','module'=>'admin'),null,true);
    }    
    
    public function integridadeFilesAction(){
        $integri = new Admin_Services_FileIntegrity();
        $html = $integri->specificCheckDiscrepancies()->getDiscrepancies();
        
        $this->view->assign('title','Integridade dos arquivos do sistema');
        $this->view->assign('list',$html);
    }
    
    public function indexAction()
    {
        $this->view->assign('title','Integridade do sistema');
    }

}

