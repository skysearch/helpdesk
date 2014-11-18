<?php

class Helpdesk_DepartamentoController extends Sky_Controller_Action_Crud
{

    public function init() {
        $this->_setUrl(array('action'=>'index','controller'=>'departamento','module'=>'helpdesk'));
        $this->_setClass(Sky_Model_Factory::getInstance()->setModule('helpdesk')->getDepartamento());
        $this->_setColumns(array(
            'departamento_id'=>array('ignore','label'=>'Id','width'=>'3%'),
            'nome'=>array('type'=>'strip_tags','label'=>'Nome','width'=>'70%'),
            'status'=>array('type'=>'switch',
                            'cases'=>array('type'=>'bool','args'=>array('Ativo','Inativo')),'label'=>'Status','width'=>'10%')
        ));
    }

    public function indexAction()
    {
        $this->view->assign('title','Gerenciamento de Departamentos');
        $this->render('index');
        $this->render('listar');
    }
    
    public function adicionarAction(){
        $form = new Helpdesk_Forms_Departamento();
        $form->jqueryValidate(true);
        
        if($this->getRequest()->isPost()){
            $data = $this->getRequest()->getPost();
            
            /*
             * Verifica se o nome do nivel já existe
             */
            $form->getElement('departamento_id')
                    ->addValidator('RecordExist',true,array('Helpdesk','Departamento','getById'));
            
            if($form->isValid($data)){
                $data = $form->getValues();
                
                if(empty($data['nome'])) {
                    $acl = Sky_Model_Factory::getInstance()->setModule('admin')->getAcl();
                    $role = $acl->getRoleById($data['departamento_id']);
                    
                    $data['nome'] = $role['description'];
                }
                
                $departamento = Sky_Model_Factory::getInstance()->setModule('helpdesk')->getDepartamento();

                /*
                 * Insere primeiro as informações do nível
                 * e adiciona o id a variavel
                 */
                $id = $departamento->insert($data);
                
              
                if($id>0){
                    $this->_helper->getHelper('FlashMessenger')
                            ->addMessage('Departamento cadastrado com sucesso.','sucess');
                    $this->_helper->redirector->goToRoute(array('action'=>'adicionar'));
                } 
            } else {
                $form->populate($data);
            }
        }
        
        $this->view->assign('form',$form);
        $this->view->assign('title','Novo Departamento');
    }

}