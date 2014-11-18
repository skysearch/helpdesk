<?php

class Admin_CategoriaController extends Sky_Controller_Action_Crud
{

    public function init()
    {
        $this->_setUrl(array('action'=>'index','controller'=>'categoria','module'=>'admin'));
        $this->_setClass(Sky_Model_Factory::getInstance()->setModule('admin')->getCategoria());
        $this->_setColumns(array(
            'categoria_id'=>array('ignore','label'=>'Id','width'=>'3%'),
            'name'=>array('type'=>'strip_tags','label'=>'Nome','width'=>'34%'),
            'description'=>array('type'=>'strip_tags','label'=>'Descrição','width'=>'34%'),
            'filter'=>array('type'=>'strip_tags','label'=>'Filter','width'=>'34%'),
            'parent'=>array('type'=>'strip_tags','label'=>'Parent','width'=>'34%')
        ));
    }

    public function indexAction()
    {
        $this->view->assign('title','Gerenciamento de Categorias');
        $this->render('index');
        $this->render('listar');
    }

    public function editarAction(){
        $id = $this->getRequest()->getParam('id');
        
        $form = new Admin_Forms_Categoria();
        $form->jqueryValidate(true);
        
        $row = $this->_class->getById($id);
        if(count($row)>0){
            $form->populate($row->toArray());
        }
        
        
         if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();


            if ($form->isValid($post)) {
                
                $data = $form->getValues($post);
                if($this->_class->update($data,$id)){
                    $this->_helper->getHelper('FlashMessenger')
                            ->addMessage('Categoria editada com sucesso.', 'sucess');
                    $this->_helper->redirector->goToRoute($this->_url,null,true);
                } 
                
            } else {
                $form->populate($post);
            }
         }
         
        $this->view->assign('form', $form);
        $this->view->assign('title', 'Cadastro de Categoria');
    }
    
    public function adicionarAction(){
        $form = new Admin_Forms_Categoria();
        $form->jqueryValidate(true);
        
         if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();


            if ($form->isValid($post)) {
                
                $data = $form->getValues($post);
                if($this->_class->insert($data)){
                    $this->_helper->getHelper('FlashMessenger')
                            ->addMessage('Categoria cadastrada com sucesso.', 'sucess');
                    $this->_helper->redirector->goToRoute($this->_url,null,true);
                }
                
            } else {
                $form->populate($post);
            }
         }
         
        $this->view->assign('form', $form);
        $this->view->assign('title', 'Cadastro de Categoria');
    }

    public function getCategoriaAction(){
        $this->_helper->getHelper('viewRenderer')->setNoRender();
	$this->_helper->getHelper('layout')->disableLayout();
        
        $data = $this->getRequest()->getPost();
 
        $categorias = $this->_class->getByParent($data['id']);
        
        if(count($categorias)>0){
            print(Zend_Json::encode($categorias->toArray()));
        }
    }
}

