<?php

class Mail_ModelController extends Sky_Controller_Action_Crud
{


    
    public function init()
    {
        $this->_setUrl(array('action'=>'index','controller'=>'model','module'=>'mail'));
        $this->_setClass(Sky_Model_Factory::getInstance()->setModule('mail')->getTemplate());
        $this->_setColumns(array(
            'template_id'=>array('ignore','label'=>'Id','width'=>'3%'),
            'name'=>array('type'=>'strip_tags','label'=>'Nome','width'=>'37%'),
            'description'=>array('type'=>'strip_tags','label'=>'Descrição','width'=>'45%'),
        ));
    }

    public function indexAction()
    {
        $this->view->assign('title','Gerenciamento de Modelos de E-mail');
        $this->render('index');
        $this->render('listar');
    }

    public function adicionarAction(){
        $form = new Mail_Forms_Layout();
        $form->jqueryValidate(true);
        
        if($this->getRequest()->isPost()){
            $data = $this->getRequest()->getPost();

            if($form->isValid($data)){
                $data = $form->getValues();
                $mail = Sky_Model_Factory::getInstance()->setModule('Mail')->getTemplate();
                
                $id = $mail->insert($data);
              
                if($id>0){
                    $this->_helper->getHelper('FlashMessenger')
                            ->addMessage('Modelo cadastrado com sucesso.','sucess');
                    $this->_helper->redirector->goToRoute(array('action'=>'adicionar'));
                } 
            } else {
                $form->populate($data);
            }
        }
        
        $this->view->assign('form',$form);
        $this->view->assign('title','Novo Modelo');
    }
    
    public function editarAction(){
        $id = $this->getRequest()->getParam('id');
        
        $form = new Mail_Forms_Layout();
        $form->jqueryValidate(true);
        
        $mail = Sky_Model_Factory::getInstance()->setModule('Mail')->getTemplate();
        $data = $mail->getById($id);
        
        if(count($data)>0) {
            $form->populate($data->toArray());
        }
        
        if($this->getRequest()->isPost()){
            $post = $this->getRequest()->getPost();

            if($form->isValid($post)){
                $data = $form->getValues();
               
                if($mail->update($data, $id)){
                    $this->_helper->getHelper('FlashMessenger')
                            ->addMessage('Modelo editado com sucesso.','sucess');
                    $this->_helper->redirector->goToRoute(array('action'=>'index','controller'=>'model','module'=>'mail'),null,true);
                } 
            } else {
                $form->populate($data);
            }
        }
        
        $this->view->assign('user_id',$id);
        $this->view->assign('form',$form);
        $this->view->assign('title','Editando Usuário');
        
    }

   
}

