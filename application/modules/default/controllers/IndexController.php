<?php

class IndexController extends Zend_Controller_Action {

    public function init() {
        Zend_Layout::getMvcInstance()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
    }

    public function indexAction() {
        //$this->_helper->redirector->goToRoute(array('action' => 'login', 'controller' => 'auth', 'module' => 'admin'), null, true);
        Zend_Layout::getMvcInstance()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        
        $tbClientes = new Application_Model_Default_Table_Helpdesk_Cliente();
        $clientes = $tbClientes->fetchAll(array("cnpj <> ''"));
        
        $tbCategoria = new Application_Model_Default_Table_Admin_Categoria();
        
        $tbUserInfo = new Application_Model_Default_Table_Admin_UserInfo();
        $tbUserUser = new Application_Model_Default_Table_Admin_User();
        
        $ok = array();
        
        foreach($clientes as $cliente){
            $categoria = $tbCategoria->createRow();
            $categoria->name = $cliente->cnpj;
            $categoria->description = $cliente->fantasia;
            $categoria->filter = 'cliente';
            $categoria->extra = serialize($cliente);
            if($categoria->save()){
                $info = $tbUserInfo->createRow();
                $info->razao = $cliente->razao;
                $info->name = $cliente->fantasia;
                $info->cnpj = $cliente->cnpj;
                $info->contato = $cliente->contato;
                $info->telefone = $cliente->telefone;
                $info->endereco = $cliente->endereco;
                $info->bairro = $cliente->bairro;
                $info->cidade = $cliente->cidade;
                $info->estado = $cliente->estado;
                $info->cep = $cliente->cep;
                $info->dominio = $cliente->dominio;
                $info->email_contato = $cliente->email_contato;
                $info->email = $cliente->email;
                $info->obs = $cliente->obs;
                $info->cliente = array($categoria->name);
                $info->created = $cliente->data_cadastro;
                        
                if($info->save()){
                    $user = $tbUserUser->createRow();
                    $user->user_info_id = $info->user_info_id;
                    $user->username = $cliente['cnpj'];
                    $user->password = $cliente['telefone'];
                    $user->status = 1;
                    $user->role_id = 2;
                    if($user->save()){
                        $ok[] = array('user_id'=>$user->user_id,'user_info_id'=>$info->user_info_id,'categoria_id'=>$categoria->categoria_id);
                    }
                }
            }
        }
        
        Zend_Debug::dump($ok);
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