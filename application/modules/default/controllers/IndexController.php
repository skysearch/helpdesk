<?php

class IndexController extends Zend_Controller_Action {

    public function init() {
        Zend_Layout::getMvcInstance()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
    }

    public function indexAction() {
        $this->_helper->redirector->goToRoute(array('action' => 'login', 'controller' => 'auth', 'module' => 'admin'), null, true);
    }
    
    public function convertAction(){
        
    }

    /*public function testeAction() {
        $Reader = new Edi_File_Reader();
        $Configs = Sky_Model_Factory::getInstance()->setModule('Varejo')->getArquivoConfig();
        

        
        
        $File = new Edi_File();
        $Folder = new Edi_Folder();
        
        $folders = $Folder->listFolders();

        Zend_Debug::dump($folders,'Path');
        
        foreach ($folders as $folder) {
            $path = $Folder->getByCnpj($folder);
            $files = $File->listFiles($path);
            
            foreach ($files as $file) {
                $ext = explode('.', $file);
                $config = $Configs->getByCnpj($folder);
                $Reader->factory($config['type'], $config->toArray());
                echo "Caminho: $path | File: {$file}<br />";
                $Reader->open($path.DS.$file);
                Zend_Debug::dump($Reader->getFile());
            }
        }
    }*/

}