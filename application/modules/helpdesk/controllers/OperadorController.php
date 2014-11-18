<?php

class Helpdesk_OperadorController extends Sky_Controller_Action_Crud
{

    public function init() {
        $this->_setUrl(array('action'=>'index','controller'=>'operador','module'=>'helpdesk'));
        $this->_setClass(Sky_Model_Factory::getInstance()->setModule('helpdesk')->getOperador());
        $this->_setColumns(array(
            'operador_id'=>array('ignore','label'=>'Id','width'=>'3%'),
            'nome'=>array('type'=>'strip_tags','label'=>'Nome','width'=>'70%'),
            'status'=>array('type'=>'switch',
                            'cases'=>array('type'=>'bool','args'=>array('Ativo','Inativo')),'label'=>'Status','width'=>'10%')
        ));
    }

    public function indexAction()
    {
        $this->view->assign('title','Gerenciamento de Operadores');
        $this->render('index');
        $this->render('listar');
    }
    
    public function adicionarAction(){
        $form = new Helpdesk_Forms_Operador();
        $form->jqueryValidate(true);
        
        if($this->getRequest()->isPost()){
            $data = $this->getRequest()->getPost();
            
            /*
             * Verifica se o nome do nivel já existe
             */
            $form->getElement('operador_id')
                    ->addValidator('RecordExist',true,array('Helpdesk','Operador','getById'));
            
            if($form->isValid($data)){
                $data = $form->getValues();
                
                if(empty($data['nome'])) {
                    $user = Sky_Model_Factory::getInstance()->setModule('admin')->getUser();
                    $u = $user->getInfoByUserId($data['operador_id']);
                    
                    $data['nome'] = $u['name'];
                }
                
                $operador = Sky_Model_Factory::getInstance()->setModule('helpdesk')->getOperador();

                /*
                 * Insere primeiro as informações do nível
                 * e adiciona o id a variavel
                 */
                $id = $operador->insert($data);
                
              
                if($id>0){
                    $this->_helper->getHelper('FlashMessenger')
                            ->addMessage('Operador cadastrado com sucesso.','sucess');
                    $this->_helper->redirector->goToRoute(array('action'=>'adicionar'));
                } 
            } else {
                $form->populate($data);
            }
        }
        
        $this->view->assign('form',$form);
        $this->view->assign('title','Novo Operador');
    }
    
    public function editarAction(){
        $id = $this->getRequest()->getParam('id');
        
        $form = new Helpdesk_Forms_Operador();
        $form->jqueryValidate(true);
        
        $operador = Sky_Model_Factory::getInstance()->setModule('helpdesk')->getOperador();
        
        $operador_data = $operador->getById($id);
        
        
        if(count($operador_data)>0) {
            $form->populate($operador_data->toArray());
        }
        
        if($this->getRequest()->isPost()){
            $data = $this->getRequest()->getPost();
            
            if($data['operador_id']!=$operador_data['operador_id'])
                $form->getElement('operador_id')
                    ->addValidator('RecordExist',true,array('Helpdesk','Operador','getById'));
            
            
            if($form->isValid($data)){
                if($operador->update($data, $id)){
                    $this->_helper->getHelper('FlashMessenger')
                            ->addMessage('Operador editado com sucesso.','sucess');
                    $this->_helper->redirector->goToRoute(array('action'=>'index','controller'=>'operador','module'=>'helpdesk'),null,true);
                } 
            } else {
                $form->populate($data);
            }
        }
        
        $this->view->assign('form',$form);
        $this->view->assign('title','Editando Operador');
    }
    
    public function apagarAction() {
    	$id = $this->getRequest()->getParam('id');
    	if($this->_class->delete($id)) {
    		$this->_helper->getHelper('FlashMessenger')
    		->addMessage('Operador apagado com sucesso.','sucess');
    	} else {
    		$this->_helper->getHelper('FlashMessenger')
    		->addMessage('Ocorreu um erro ao tentar remover o operador.','error');
    	}
    	
    	$this->_helper->redirector->goToRoute(array('action'=>'index','controller'=>'operador','module'=>'helpdesk'),null,true);
    }

}