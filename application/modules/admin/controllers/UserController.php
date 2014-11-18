<?php

class Admin_UserController extends Sky_Controller_Action_Crud
{

    public function init()
    {
        $this->_setUrl(array('action'=>'index','controller'=>'user','module'=>'admin'));
        $this->_setClass(Sky_Model_Factory::getInstance()->setModule('admin')->getUser());
        $this->_setColumns(array(
            'user_id'=>array('ignore','label'=>'Id','width'=>'3%'),
            'info_name'=>array('type'=>'strip_tags','real_name'=>'i.name','label'=>'Nome','width'=>'34%'),
            'email'=>array('type'=>'strip_tags','label'=>'E-mail','width'=>'20%'),
            'username'=>array('type'=>'strip_tags','label'=>'Usuário','width'=>'10%'),
            'description'=>array('type'=>'strip_tags','label'=>'Nível','width'=>'13%'),
            'status'=>array('type'=>'switch',
                            'cases'=>array('type'=>'bool','args'=>array('Ativo','Inativo')),'label'=>'Status','width'=>'10%')
        ));
        
    }

    public function indexAction()
    {
        $this->view->assign('title','Gerenciamento de Usuários');
        $this->render('index');
        $this->render('listar');
    }

    public function adicionarAction(){
        $form = new Admin_Forms_User();
        $form->jqueryValidate(true);
        
        if($this->getRequest()->isPost()){
            $data = $this->getRequest()->getPost();
            
            /*
             * Verifica se o nome do usuário já existe
             */
            $form->getElement('username')
                    ->addValidator('UserExist');
            
            if($form->isValid($data)){
                
                $data = $form->getValues();
                
                $user = Sky_Model_Factory::getInstance()->setModule('admin')->getUser();
                
                /*
                 * Insere primeiro as informações do usuário
                 * e adiciona o id no array para o cadastro
                 * do usuario
                 */
                $data['user_info_id'] = $user->insertInfo($data);
                
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
        $this->view->assign('title','Novo Usuário');
    }
    
    public function editarAction(){
        $id = $this->getRequest()->getParam('id');
        
        $form = new Admin_Forms_User();
        $form->jqueryValidate(true);
        $form->removeElement('password');
        
        $user = Sky_Model_Factory::getInstance()->setModule('admin')->getUser();
        $user_data = $user->getById($id);
        
        /*
         * Verifica se o usuário existe
         * se existe procura as informações
         * do mesmo na tabela de informações
         */
        if(count($user_data)>0) {
            $form->populate($user_data->toArray());
            
            $user_info_data =  $user->getInfoByUserId($id);
            $data = $user->getInfoByUserId($id)->toArray();
            
            $form->populate($data);
        }
        
        if($this->getRequest()->isPost()){
            $post = $this->getRequest()->getPost();
            
            /*
             * Verifica se o nome do usuário foi alterado
             * e se o novo nome já existe
             */
            if($post['username']!=$user_data['username'])
                $form->getElement('username')
                    ->addValidator('UserExist');
            
            
            if($form->isValid($post)){
                $data = $form->getValues();
                
                /**
                 * Esta é uma correção temporaria para 
                 * o problema na recuperação do campo
                 * de coordenada GPS do formulário
                 */
                $filter = new Zend_Filter_StripTags();
                $data['cood_postal_code'] = $filter->filter($post['cood_postal_code']);
                
                if($user->updateInfo($data, $id) && $user->update($data,$id)){
                    $this->_helper->getHelper('FlashMessenger')
                            ->addMessage('Usuário editado com sucesso.','sucess');
                    $this->_helper->redirector->goToRoute(array('action'=>'index','controller'=>'user','module'=>'admin'),null,true,true);
                } 
            } else {
                $form->populate($data);
            }
        }
        
        $this->view->assign('user_id',$id);
        $this->view->assign('form',$form);
        $this->view->assign('title','Editando Usuário');
        
    }
    
    
    public function alterarSenhaAction(){
        $id = $this->getRequest()->getParam('id');
        
        $form = new Admin_Forms_AlterarSenha();
        $form->jqueryValidate(true);
                
        $user = Sky_Model_Factory::getInstance()->setModule('admin')->getUser();
        
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
                        $route = array('action'=>'index','controller'=>'index','module'=>'admin');
                    else 
                        $route = array('action'=>'index','controller'=>'user','module'=>'admin','id'=>$id);
                    
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
    
    
    public function onlineAction() {
        $session = Sky_Model_Factory::getInstance()->setModule('admin')->getSession();
        $rows = $session->getLikeUser();
        
        
        $id = $this->getRequest()->getParam('id',null);
        if(!is_null($id)) {
            if(sha1(Zend_Session::getId())==$id) {
                $this->_helper->getHelper('FlashMessenger')
                    ->addMessage('Você não pode excluir sua própria sessão.','sucess');
                
                $this->_helper->redirector->goToRoute(array('action'=>'online','controller'=>'user','module'=>'admin'),null,true);
            }

            $row = $session->getById($id,"SHA1");
            if(count($row)>0){
                if($row->delete()) {
                    $this->_helper->getHelper('FlashMessenger')
                    ->addMessage('Usuário deslogado com sucesso.','sucess');
                } else {
                    $this->_helper->getHelper('FlashMessenger')
                    ->addMessage('Ocorreu um erro ao tentar deslogar o usuário.','error');
                }
            } else {
                $this->_helper->getHelper('FlashMessenger')
                    ->addMessage('Sessão não encontrada.','error');
            }
            $this->_helper->redirector->goToRoute(array('action'=>'online','controller'=>'user','module'=>'admin'),null,true);
        }
        
        $this->view->assign('list',$rows);
        $this->view->assign('title','Usuários Online');
    }
    
    public function apagarAction() {
    	$id = $this->getRequest()->getParam('id');
    	$user = Sky_Model_Factory::getInstance()->setModule('admin')->getUser();
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

