<?php

class Admin_AuthController extends Zend_Controller_Action {

    public function init() {
        Zend_Session::start();
        
        Zend_Layout::getMvcInstance()->setLayout('auth');

    }
    
    public function indexAction(){
        Zend_Layout::getMvcInstance()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        
        $this->_helper->redirector->goToRoute(array('action' => 'login', 'controller' => 'auth', 'module' => 'admin'), null, true);
    }

    /* ========== Frontend actions ========================================== */

    /**
     * Deny access
     * 
     * @return void
     */
    public function denyAction() {
        Zend_Layout::getMvcInstance()->setLayout('message');
    }


    /**
     * Login
     * 
     * @return void
     */
    public function loginAction() {
        
        $param = $this->getRequest()->getParam('layout',null);

        $auth 	 = Zend_Auth::getInstance();
        
        if ($auth->hasIdentity()) {
                $this->_helper->redirector->goToRoute(array('action'=>'index','controller'=>'index'));
        }
        
        $form_login = new Admin_Forms_Login();
        $form_login->jqueryValidate(true);

        $this->view->assign('title','Autenticação de Usuário');
        $this->view->assign('form_login', $form_login);

        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $data = $request->getPost();

            if (!$form_login->isValid($data)) {
                return $form_login->populate($data);
            }

            $data = $form_login->getValues();

            $username = $data['username'];
            $password = $data['password'];
            
            
            $adapter = new Admin_Services_Auth($username, $password);
            $result = $auth->authenticate($adapter);
            switch ($result->getCode()) {
                /**
                 * Found user, but the account has not been activated
                 */
                case Admin_Services_Auth::NOT_ACTIVE:
                    $this->_helper->getHelper('FlashMessenger')
                            ->addMessage('Usuário inativo.','alert');
                    $this->_helper->redirector->goToRoute(array('action'=>'login'));
                    break;

                /**
                 * Logged in successfully
                 */
                case Admin_Services_Auth::SUCCESS:
                    $this->_helper->redirector->goToRoute(array('action'=>'index','controller'=>'ticket','module'=>'helpdesk'),null,true);
                    break;

                /**
                 * Not found
                 */
                case Admin_Services_Auth::FAILURE:
                default:
                    $this->_helper->getHelper('FlashMessenger')
                            ->addMessage('Usuário ou senha inválida.','alert');
                    $this->_helper->redirector->goToRoute(array('action'=>'login'));
                    break;
            }
        }
        
        if(!is_null($param)) {
            Zend_Layout::getMvcInstance()->setLayout($param);
            $this->render('minibox');
        }
    }
    
    
    

    /**
     * Recovery
     * 
     * @return void
     */
    public function recoveryAction() {
        $form = $form_login = new Admin_Forms_Recovery();
        $form->jqueryValidate(true);
        $form->setAction($this->view->url(array('action'=>'recovery','controller'=>'auth','module'=>'admin')));
        
        if($this->getRequest()->isPost()){
            $post = $this->getRequest()->getPost();
            
            if($form->isValid($post)){
                $data = $form->getValues();
                
                $user = Sky_Model_Factory::getInstance()->setModule('admin')->getUser();
                $row = $user->recovery($data['username'],$data['email']);
                
                if($row !== false) {
                    $mail = new Sky_Mail(array('mail_history'=>0));
                    $mail->setTemplate($row, 'recovery');
                    
                    if($mail->send('Recuperação de Senha',null,$row['email'])){

                        $this->_helper->getHelper('FlashMessenger')
                            ->addMessage('Uma nova senha foi enviada para seu e-mail!','sucess');
                        
                        $mail = new Sky_Mail(array('mail_history'=>0));
                        $mail->setTemplate($row, 'recovery');
                        $mail->send('Cópia da Recuperação de Senha');
                        
                        $this->_helper->redirector->goToRoute(array('action'=>'recovery','controller'=>'auth','module'=>'admin'));
                    } else {
                        $this->_helper->getHelper('FlashMessenger')
                            ->addMessage('Não foi possivel enviar o e-mail com a nova senha.','error');
                    }
                } else {
                    $this->_helper->getHelper('FlashMessenger')
                            ->addMessage('Dados informados inválidos.','error');
                }
                
                $this->_helper->getHelper('FlashMessenger')
                            ->addMessage('Não foi possivel recuperar sua senha.','error');
                        $this->_helper->redirector->goToRoute(array('action'=>'recovery','controller'=>'auth','module'=>'admin'));
            } else {
                $form->populate($post);
            }
        }
        
        $this->view->assign('form_recovery', $form);
    }

    /**
     * Logout
     * 
     * @return void
     */
    public function logoutAction() {
        Zend_Session::destroy(false, false);
        $this->_helper->redirector->goToRoute(array('action'=>'login','module'=>'admin'));
    }
    
    /**
     * Cadastro de novo usuário
     * 
     * @return void
     */
    public function cadastroAction(){
        $form = new Cadernetas_Forms_Cadastro();
        $form->jqueryValidate(true);
        
        $form->removeElement('imagem_facebook');
        $form->removeElement('imagem');
        $form->removeDisplayGroup('DataAddPictureUser');
        
        if($this->getRequest()->isPost()){
            $post = $this->getRequest()->getPost();
            
            /*
             * Verifica se o nome do usuário já existe
             */
            $form->getElement('cpf')
                    ->addValidator('RecordExist', true, array('admin','User','cpfExists'));
            
            //print($form->isValid($data));exit;
            
            //Zend_Debug::dump($data);exit;
            
            if($form->isValid($post)){
                
                $data = $form->getValues();
                
                /**
                 * Esta é uma correção temporaria para 
                 * o problema na recuperação do campo
                 * de coordenada GPS do formulário
                 */
                $filter = new Zend_Filter_StripTags();
                //$data['postal_code'] = $filter->filter($post['postal_code']);
                $data['postal_code_coord'] = $filter->filter($post['postal_code_coord']);
                
                
                $user = Sky_Model_Factory::getInstance()->setModule('admin')->getUser();
                $acl = Sky_Model_Factory::getInstance()->setModule('admin')->getAcl();
                
                
                /*
                 * Insere primeiro as informações do usuário
                 * e adiciona o id no array para o cadastro
                 * do usuario
                 */
                $data['user_info_id'] = $user->insertInfo($data);
                
                $role = $acl->getRoleByName('cliente');
                $data['role_id'] = $role['role_id'];
                $data['username'] = $data['cpf'];
                $data['confirmed'] = 0;
                $data['status'] = 1;
                
                $id = $user->insert($data);
              
                if($id>0){
                    $mail = new Sky_Mail();
                    $mail->setTemplate($data,'cadastro');
                    $mail->send("Novo usuário - {$data['name']}");
        
                    $this->_helper->getHelper('FlashMessenger')
                            ->addMessage('Usuário cadastrado com sucesso.','sucess');
                    
                    $auth = Zend_Auth::getInstance();
                    $adapter = new Admin_Services_Auth($data['username'], $data['password']);
                    $result = $auth->authenticate($adapter);
                    
                    if ($result->getCode() == Admin_Services_Auth::SUCCESS) {
                        $this->_helper->getHelper('FlashMessenger')
                            ->addMessage('Cadastro concluído com sucesso. Bem vindo ao sistema!','sucess');
                        $this->_helper->redirector->goToRoute(array('action'=>'foto','controller'=>'user','module'=>'cadernetas'));
                    } else {
                        $this->_helper->redirector->goToRoute(array('action'=>'cadastro'));
                    }    
                } 
                
            } else {
                $form->populate($post);
            }
        }

        $this->view->assign('form',$form);
        $this->view->assign('title','Cadastro de Usuário');
    }
    
    
    /*public function fotoAction(){
       
       $form = new Cadernetas_Forms_Foto();
       $form->jqueryValidate(true);
       
       $auth = Zend_Auth::getInstance();

       if (!$auth->hasIdentity()) {
                $this->_helper->redirector->goToRoute(array('action'=>'login','controller'=>'auth'));
        }
       
        $info = $auth->getIdentity();
        

        if($this->getRequest()->isPost()){
            $data = $this->getRequest()->getPost();
            if($form->isValid($data)){
                $name = null;
                
                if ($form->imagem->isUploaded()) {
                    $file = $form->imagem->getFileInfo();
                    if($form->imagem->isValid($file)){
                       $arquivo = explode('.',$file['imagem']['name']);
                       $name = $info['user']['user_id'] . '.' . end($arquivo);
                       $form->imagem->addFilter('Rename',$name);
                       $form->imagem->receive();
                    } else {
                        $form->populate($data);
                    }
                    
                } else {
                    $facebook = Sky_Model_Factory::getInstance()->setModule('facebook')->getProfile();
                    $picture = $facebook->getUser()->getpicture('large');
                    $name = $info['user']['user_id'] . '.jpg';

                    $file = $this->_helper->getHelper('File')
                                            ->downloadFile($picture, PUBLIC_PATH . DS . 'uploads' . DS . 'avatar' . DS .  $name,array(
                                                            'followLocation' => true,
                                                            'maxRedirs' => 5,
                                                            ));
                }
                
                if(!is_null($name)) {
                    $user = Sky_Model_Factory::getInstance()->setModule('admin')->getUser();
                    $row = $user->getInfoByUserId($info['user']['user_id']);
                    
                    if(count($row)>0) {
                        $row->avatar = $name;
                        $row->save();
                        
                        $this->_helper->getHelper('FlashMessenger')
                            ->addMessage('Cadastro concluído com sucesso. Bem vindo ao sistema!','sucess');
                        
                         $this->_helper->redirector->goToRoute(array('action'=>'index','controller'=>'index'));
                    }
                }
                
            } else {
                $form->populate($data);
            }
        }
        
       $this->view->assign('form',$form);
       $this->view->assign('title','Cadastro de Usuário');
    }*/
}