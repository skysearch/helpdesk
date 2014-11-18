<?php

class Api_NivelController extends Zend_Controller_Action
{

    public function indexAction()
    {
        $this->view->assign('title','Gerenciamento de Níveis de Usuários API');
        $this->render('index');
        $this->render('listar');
    }

    public function adicionarAction(){
        $form = new Api_Forms_Nivel();
        $form->jqueryValidate(true);
        
        if($this->getRequest()->isPost()){
            $data = $this->getRequest()->getPost();
            
            /*
             * Verifica se o nome do nivel já existe
             */
            $form->getElement('name')
                    ->addValidator('RecordExist',true,array('Acl','roleExist'));
            
            if($form->isValid($data)){
                $acl = Sky_Model_Factory::getInstance()->setModule('api')->getAcl();

                /*
                 * Insere primeiro as informações do nível
                 * e adiciona o id a variavel
                 */
                $id = $acl->insertRole($data);
                
                /*
                 * Insere rules do nivel na tabela
                 */
                $acl->insertRule($data,$id,1);
              
                if($id>0){
                    $this->_helper->getHelper('FlashMessenger')
                            ->addMessage('Nível cadastrado com sucesso.','sucess');
                    $this->_helper->redirector->goToRoute(array('action'=>'adicionar'));
                } 
            } else {
                $form->populate($data);
            }
        }
        
        $this->view->assign('form',$form);
        $this->view->assign('title','Novo Nível de Usuário API');
    }
    
    public function editarAction(){
        $id = $this->getRequest()->getParam('id');
        
        $form = new Api_Forms_Nivel();
        $form->jqueryValidate(true);
        
        $acl = Sky_Model_Factory::getInstance()->setModule('api')->getAcl();
        
        /*
         * Verifica se o nome do nivel existe
         * se existe procura as informações
         * do mesmo na tabela de niveis
         */
        $role_data = $acl->getRoleById($id);
        
        
        if(count($role_data)>0) {
            $form->populate($role_data->toArray());
            
            /*
             * Seleciona no banco os rules 
             * do nivel que está sendo editado
             */
            $rules_data =  $acl->getRulesByRole($id);

            $rule = array();
            foreach($rules_data as $r){
                $n = str_replace(':', '_', $r['resource_name']);
                $rule[$n][] = $r['privilege_id'];
            }
            
            $form->populate($rule);
        }
        
        if($this->getRequest()->isPost()){
            $data = $this->getRequest()->getPost();
            
            /*
             * Verifica se o nome do nível foi alterado
             * e se o novo nome já existe
             */
            if((strtolower($data['name'])!=strtolower($role_data['name'])) || 
                    (strtolower($data['description'])!=strtolower($role_data['description'])))
                $form->getElement('name')
                    ->addValidator('RecordExist',true,array('Acl','roleExist'));
            
            
            if($form->isValid($data)){
                
                if($acl->updateRole($data, $id)){
                    
                    $acl->insertRule($data,$id,1);
                    
                    $this->_helper->getHelper('FlashMessenger')
                            ->addMessage('Nível editado com sucesso.','sucess');
                    $this->_helper->redirector->goToRoute(array('action'=>'index','controller'=>'nivel','module'=>'api'),null,true);
                } 
            } else {
                $form->populate($data);
            }
        }
        
        $this->view->assign('form',$form);
        $this->view->assign('title','Editando Nível de Usuário API');
        
    }

    
    public function listarAction(){
        $this->_helper->getHelper('viewRenderer')->setNoRender();
	$this->_helper->getHelper('layout')->disableLayout();
        
        $post = $this->getRequest()->getPost();
        $acl = Sky_Model_Factory::getInstance()->setModule('api')->getAcl();
        
        /*
         * Array com os campos a serem utilizados 
         * na tabela de listagem
         */
        $columns = array(
            'role_id'=>array('ignore'),
            'description'=>array('type'=>'strip_tags'),
            'locked'=>array('type'=>'switch',
                            'cases'=>array('type'=>'bool','args'=>array('Ativo','Inativo')))
        );
        $json = $acl->getToDataTable($post,$columns);
       
        print($json);
    }
    
    
    public function apagarAction() {
    	$id = $this->getRequest()->getParam('id');
    	$role = Sky_Model_Factory::getInstance()->setModule('folheados')->getAcl();
    	if($role->deleteRole($id)) {
    		$this->_helper->getHelper('FlashMessenger')
    		->addMessage('Nível apagado com sucesso.','sucess');
    	} else {
    		$this->_helper->getHelper('FlashMessenger')
    		->addMessage('Ocorreu um erro ao tentar remover o nível.','error');
    	}
    	
    	$this->_helper->redirector->goToRoute(array('action'=>'index','controller'=>'nivel','module'=>'api'),null,true);
    }
}

