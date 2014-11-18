<?php

class Api_UserController extends Zend_Controller_Action
{

    public function init()
    {
        /*$ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('listar', 'json')
                    ->initContext();*/
    }

    public function indexAction()
    {
        $this->view->assign('title','Gerenciamento de Usuários API');
        $this->render('index');
        $this->render('listar');
    }

    public function adicionarAction(){
        $form = new Api_Forms_User();
        $form->jqueryValidate(true);
        
        if($this->getRequest()->isPost()){
            $data = $this->getRequest()->getPost();
            
            /*
             * Verifica se o nome do usuário já existe
             */
            $form->getElement('username')
                    ->addValidator('UserExist');
            
            if($form->isValid($data)){
                $user = Sky_Model_Factory::getInstance()->setModule('api')->getUser();
                
                $id = $user->insert($data);
              
                if($id>0){
                    $this->_helper->getHelper('FlashMessenger')
                            ->addMessage('Usuário cadastrado com sucesso.','sucess');
                    $this->_helper->redirector->goToRoute(array('action'=>'adicionar'));
                } 
            } else {
                $form->populate($data);
            }
        }
        
        $this->view->assign('form',$form);
        $this->view->assign('title','Novo Usuário API');
    }
    
    public function editarAction(){
        $id = $this->getRequest()->getParam('id');
        
        $form = new Api_Forms_User();
        $form->jqueryValidate(true);
        $form->removeElement('token');
        
        $user = Sky_Model_Factory::getInstance()->setModule('api')->getUser();
        $user_data = $user->getById($id);
        
        /*
         * Verifica se o usuário existe
         * se existe procura as informações
         * do mesmo na tabela de informações
         */
        if(count($user_data)>0) {
            $form->populate($user_data->toArray());
        }
        
        if($this->getRequest()->isPost()){
            $data = $this->getRequest()->getPost();
            
            /*
             * Verifica se o nome do usuário foi alterado
             * e se o novo nome já existe
             */
            if($data['username']!=$user_data['username'])
                $form->getElement('username')
                    ->addValidator('UserExist');
            
            
            if($form->isValid($data)){
                if($user->update($data,$id)){
                    $this->_helper->getHelper('FlashMessenger')
                            ->addMessage('Usuário editado com sucesso.','sucess');
                    $this->_helper->redirector->goToRoute(array('action'=>'index','controller'=>'user','module'=>'api'),null,true);
                } 
            } else {
                $form->populate($data);
            }
        }
        
        $this->view->assign('user_id',$id);
        $this->view->assign('form',$form);
        $this->view->assign('title','Editando Usuário API');
        
    }
    
    
    public function alterarSenhaAction(){
        $id = $this->getRequest()->getParam('id');
        
        $form = new Api_Forms_AlterarSenha();
        $form->jqueryValidate(true);
                
        $user = Sky_Model_Factory::getInstance()->setModule('api')->getUser();
        
        /*
         * Se id estiver vazio altera a senha do 
         * usuário atual e para isso usa o validador
         */
        if(empty($id)){
            /*
             * Os dados serão carregados da sessão autenticada
             */
            $auth = Zend_Auth::getInstance();
            $user_data = $auth->getIdentity();
            $user_data = $user_data['user'];
            $form->getElement('atual')
                    ->addValidator('PasswordConfirmation');
            
            $id = $user_data['user_id'];
        } else {
            $user_data = $user->getById($id);
            $form->setAction($this->view->url(
                    array('action'=>'alterar-senha','controller'=>'user','module'=>'admin','id'=>$id),null,true));
            $form->removeElement('atual');
        }

        
        if($this->getRequest()->isPost()){
            $data = $this->getRequest()->getPost();

            if($form->isValid($data)){
                if($user->update($data,$id)){
                    $this->_helper->getHelper('FlashMessenger')
                            ->addMessage('Senha alterada com sucesso.','sucess');
                    
                    if(!empty($data['atual']))
                        $route = array('action'=>'index','controller'=>'index','module'=>'api');
                    else 
                        $route = array('action'=>'index','controller'=>'user','module'=>'api','id'=>$id);
                    
                } else {
                    $this->_helper->getHelper('FlashMessenger')
                            ->addMessage('Ocorreu um erro ao tentar alterar a senha.','error');
                    $route = array('action'=>'alterar-senha','controller'=>'user','module'=>'admin','id'=>$id);
                }
                
                $this->_helper->redirector->goToRoute($route,null,true);
            } 
        }
        
        $this->view->assign('form_alterar_senha',$form);
        $this->view->assign('title','Alterar Senha');
    }
    
    public function listarAction(){
        $this->_helper->getHelper('viewRenderer')->setNoRender();
	$this->_helper->getHelper('layout')->disableLayout();
        
        $post = $this->getRequest()->getPost();
        $user = Sky_Model_Factory::getInstance()->setModule('api')->getUser();
        
        /*
         * Array com os campos a serem utilizados 
         * na tabela de listagem
         */
        $columns = array(
            'user_id'=>array('ignore'),
            'name'=>array('type'=>'strip_tags'),
            'email'=>array('type'=>'strip_tags'),
            'username'=>array('type'=>'strip_tags'),
            'description'=>array('type'=>'strip_tags'),
            'status'=>array('type'=>'switch',
                            'cases'=>array('type'=>'bool','args'=>array('Ativo','Inativo')))
        );
        $json = $user->getToDataTable($post,$columns);
       
        
        print($json);
    }
    
    
    public function apagarAction() {
    	$id = $this->getRequest()->getParam('id');
    	$user = Sky_Model_Factory::getInstance()->setModule('api')->getUser();
    	if($user->deleteUser($id)) {
            
    		$this->_helper->getHelper('FlashMessenger')
    		->addMessage('Usuário apagado com sucesso.','sucess');
    	} else {
    		$this->_helper->getHelper('FlashMessenger')
    		->addMessage('Ocorreu um erro ao tentar remover o usuário.','error');
    	}
    	
    	$this->_helper->redirector->goToRoute(array('action'=>'index','controller'=>'user','module'=>'admin'),null,true);
    }
}

