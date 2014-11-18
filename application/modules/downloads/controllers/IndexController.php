<?php

class Downloads_IndexController extends Zend_Controller_Action {

    protected $_class = null;

    public function init() {
        $this->_class = Sky_Model_Factory::getInstance()->setModule('downloads')->getPasta();
        Zend_Layout::getMvcInstance()->setLayout('bootstrap');
    }

    public function indexAction() {
        $this->view->assign('title', 'Gerenciamento de Pastas');
    }

    public function adicionarAction() {
        $auth = Zend_Auth::getInstance();
        $user = $auth->getIdentity();

        $form = new Downloads_Forms_Pasta();
        $form->jqueryValidate(true);
        $form->jqueryTabs(false);

        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();


            if ($form->isValid($post)) {
                $data = $form->getValues($post);
                $id = $this->_class->create($data);

                if ($id > 0) {
                    $row = $this->_class->getById($id, 'line');
                    //$this->_sendMail($row,'aviso-nova-pasta','Nova Pasta');
                    $this->_helper->getHelper('FlashMessenger')
                            ->addMessage('Pasta criada com sucesso.', 'sucess');
                } else {
                    $this->_helper->getHelper('FlashMessenger')
                            ->addMessage('Não foi possível criar a Pasta.', 'error');
                }

                $this->_helper->redirector->goToRoute(array('action' => 'arquivos','id'=>$id));
            } else {
                $form->populate($post);
            }
        }

        $this->view->assign('form', $form);
        $this->view->assign('title', 'Pastas');
        $this->view->assign('user',$user['role']);
    }

    public function arquivosAction() {
        $id = $this->getRequest()->getParam('id');

        $auth = Zend_Auth::getInstance();
        $user = $auth->getIdentity();
       

        $form = "";
        $row = $this->_class->getById($id);
        $andamento = array();

        if (count($row) > 0) {
            if($user['role']['name'] <> 'cliente'){
                $form = new Downloads_Forms_Arquivo();
                $form->jqueryValidate(true);
                $form->jqueryTabs(false);
            }

            $andamentos = Sky_Model_Factory::getInstance()->setModule('downloads')->getArquivo();
            $andamento = $andamentos->getByPasta($row['pasta_id']);

            $this->view->assign('arquivo', $andamento);
            $this->view->assign('pasta', $row);
        }

        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();

            if ($form->isValid($post)) {
                $data = $form->getValues($post);

                $file_name = $form->getElement('arquivo')->getValue();
                $file_newname = '';

                if (!empty($file_name)) {
                    $file_array = explode('.', $file_name);
                    $file_newname = md5($file_name) . rand(1, 99) . "." . end($file_array);

                    $origem = PUBLIC_PATH . DS . 'uploads' . DS . 'docs';
                    $destino = $origem . DS . $id;
                    if (!is_dir($destino)) {
                        mkdir($destino);
                    }
                    if (copy($origem . DS . $file_name, $destino . DS . $file_newname)) {
                        unlink($origem . DS . $file_name);
                    }

                    $data['arquivo'] = array('name' => base64_encode($file_newname), 'real_name' => base64_encode($file_name));
                }

                $data_row['pasta_id'] = $row['pasta_id'];
                $data_row['proprietario'] = $user['user']['user_id'];
                $data_row['nome'] = $row['nome'];

                $data = array_merge($data_row, $data);

                $id = $andamentos->insert($data);
                
                //$this->_sendMail($data,'atualizacao-da-pasta','Pasta Atualizada');

                $this->_helper->redirector->goToRoute(array('action' => 'arquivos'));
            } else {
                $form->populate($post);
            }
        }

        $this->view->assign('departamento', $user['role']);
        $this->view->assign('pasta', $row);
        $this->view->assign('user',$user['role']);
        
        $this->view->assign('form', '');
        if($user['role']['name']=='asm' || $user['role']['name']=='administrador'){
            $this->view->assign('form', $form);
        }
        
        $this->view->assign('title', 'Pasta de Downloads');
    }

    public function editarAction() {
        $id = $this->getRequest()->getParam('id');

        $auth = Zend_Auth::getInstance();
        $user = $auth->getIdentity();

        $row = $this->_class->getById($id);
        $data = $row->toArray();

        $form = new Downloads_Forms_Pasta();
        $form->jqueryValidate(true);
        $form->jqueryTabs(false);
        $form->getElement('salvar')->setLabel('Atualizar Pasta');

        if(!is_array($data['departamento_id'])){
            $data['departamento_id'] = unserialize($data['departamento_id']);
        } 
        //Zend_Debug::dump($data);

        $form->populate($data);

        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();

            if ($form->isValid($post)) {
                $data = $form->getValues($post);

                $id = (bool) $this->_class->update($data, $id);

                if ($id) {
                    $this->_helper->getHelper('FlashMessenger')
                            ->addMessage('Pasta atualizada com sucesso.', 'sucess');
                } else {
                    $this->_helper->getHelper('FlashMessenger')
                            ->addMessage('Não foi possível atualizar a Pasta.', 'error');
                }

                $this->_helper->redirector->goToRoute(array('action' => 'editar'));
            } else {
                $form->populate($post);
            }
        }

        $this->view->assign('form', $form);
        $this->view->assign('title', 'Pastas');
    }

    public function listarAction() {
        $params = array();
        
        $auth = Zend_Auth::getInstance();
        $info = $auth->getIdentity();

        $params['grupo']=$info['user']['grupo'];
        
        $pastas = $this->_class->get($params);
        $this->view->assign('pastas', $pastas);

        $this->view->assign('departamento', $info['role']);
        $this->view->assign('title', 'Pastas');
    }

    public function apagarAction() {
        $id = $this->getRequest()->getParam('id');

        if ($this->_class->delete($id)) {
            $this->_helper->getHelper('FlashMessenger')
                    ->addMessage('Pasta apagada com sucesso.', 'sucess');
        } else {
            $this->_helper->getHelper('FlashMessenger')
                    ->addMessage('Ocorreu um erro ao tentar remover a pasta.', 'error');
        }

        $this->_helper->redirector->goToRoute(array('action' => 'listar', 'controller' => 'index', 'module' => 'downloads'), null, true);
    }
    
    public function apagarArquivoAction() {
        $id = $this->getRequest()->getParam('id');
        $andamentos = Sky_Model_Factory::getInstance()->setModule('downloads')->getArquivo();
        $row = $andamentos->getById($id);
        
        if (count($row)>0) {
            $origem = PUBLIC_PATH . DS . 'uploads' . DS . 'docs';
            $destino = $origem . DS . $row['pasta_id'] . DS . base64_decode($row['arquivo']['name']);
            
            @unlink($destino);
            $andamentos->delete($id);
            
            $this->_helper->getHelper('FlashMessenger')
                    ->addMessage('Arquivo apagado com sucesso.', 'sucess');
        } else {
            $this->_helper->getHelper('FlashMessenger')
                    ->addMessage('Ocorreu um erro ao tentar remover a arquivo.', 'error');
        }

        $this->_helper->redirector->goToRoute(array('action' => 'arquivos', 'controller' => 'index', 'module' => 'downloads','id'=>$row['pasta_id']), null, true);
    }

    protected function _sendMail($row,$template,$subject) {
        $proprietario = Sky_Model_Factory::getInstance()->setModule('admin')->getUser()->getInfoByUserId($row['proprietario']);
        $operador = Sky_Model_Factory::getInstance()->setModule('admin')->getUser()->getInfoByUserId($row['operador_id']);
        
        $mail = new Sky_Mail();
        $mail->setTemplate($row, $template);
        $mail->send("{$subject} - {$row['assunto']}", null, $proprietario['email']);

        if (count($operador) > 0) {
            $mail = new Sky_Mail();
            $mail->setTemplate($row, "{$template}-operador");
            $mail->send("{$subject} - {$row['assunto']}", null, $operador['email']);
        }
    }

}
