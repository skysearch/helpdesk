<?php

class Helpdesk_TicketController extends Zend_Controller_Action {

    protected $_class = null;

    public function init() {
        $this->_class = Sky_Model_Factory::getInstance()->setModule('helpdesk')->getTicket();
        Zend_Layout::getMvcInstance()->setLayout('bootstrap');
    }

    public function indexAction() {
        $this->view->assign('title', 'Gerenciamento de Chamados');
    }

    public function adicionarAction() {
        $auth = Zend_Auth::getInstance();
        $user = $auth->getIdentity();

        $form = new Helpdesk_Forms_Ticket(array('level' => $user['role']['name']));
        $form->jqueryValidate(true);
        $form->jqueryTabs(false);

        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();


            if ($form->isValid($post)) {
                $data = $form->getValues($post);
                $id = $this->_class->create($data);

                $file_name = $form->getElement('arquivo')->getValue();
                $file_newname = '';
                $origem = PUBLIC_PATH . DS . 'uploads' . DS . 'docs';

                if ($id > 0) {
                    $row = $this->_class->getById($id, 'line');

                    if (!empty($file_name)) {
                        $file_array = explode('.', $file_name);
                        $file_newname = md5($file_name) . rand(1, 99) . "." . end($file_array);

                        $destino = $origem . DS . $id;
                        if (!is_dir($destino)) {
                            mkdir($destino);
                        }
                        if (copy($origem . DS . $file_name, $destino . DS . $file_newname)) {
                            unlink($origem . DS . $file_name);
                        }

                        $row->arquivo = array('name' => $file_newname, 'real_name' => $file_name);
                        $row->save();
                    }

                    $this->_sendMail($row, 'aviso-novo-chamado', 'Novo Chamado');

                    $this->_helper->getHelper('FlashMessenger')
                            ->addMessage('Chamado criado com sucesso.', 'sucess');
                } else {

                    if (!empty($file_name)) {
                        unlink($origem . DS . $file_name);
                    }

                    $this->_helper->getHelper('FlashMessenger')
                            ->addMessage('Não foi possível criar o Chamado.', 'error');
                }

                $this->_helper->redirector->goToRoute(array('action' => 'listar'));
            } else {
                //$form->populate($post);
            }
        }

        $this->view->assign('form', $form);
        $this->view->assign('title', 'Chamados');
    }

    public function andamentoAction() {
        $id = $this->getRequest()->getParam('id');

        $auth = Zend_Auth::getInstance();
        $user = $auth->getIdentity();

        $row = $this->_class->getById($id);
        $andamento = array();

        if (count($row) > 0) {

            if ($row['status'] == 'novo-chamado' && $row['operador_id'] == $user['user']['user_id']) {
                $row_in = $this->_class->getById($id, 'line');
                $row_in->status = 'em-andamento';
                $row_in->save();
            }

            $form = new Helpdesk_Forms_Andamento(array('level' => $user['role']['name']));
            $form->jqueryValidate(true);
            $form->jqueryTabs(false);

            $andamentos = Sky_Model_Factory::getInstance()->setModule('helpdesk')->getAndamento();
            $andamento = $andamentos->getByTicket($row['ticket_id']);

            $this->view->assign('andamento', $andamento);
            $this->view->assign('ticket', $row);
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

                    $data['arquivo'] = array('name' => $file_newname, 'real_name' => $file_name);
                }

                $data_row['ticket_id'] = $row['ticket_id'];
                $data_row['operador_id'] = $row['operador_id'];
                $data_row['prazo'] = $row->prazo->format('Y-m-d H:i');
                $data_row['proprietario'] = $user['user']['user_id'];
                $data_row['assunto'] = $row['assunto'];

                $data = array_merge($data_row, $data);

                $id = $andamentos->insert($data);

                $this->_sendMail($data, 'atualizacao-do-chamado', 'Chamado Atualizado');

                $this->_helper->redirector->goToRoute(array('action' => 'andamento'));
            } else {
                $form->populate($post);
            }
        }

        $this->view->assign('tiket', $row);
        $this->view->assign('operador', array('operador_id' => $user['user']['user_id'], 'role_name' => $user['role']['name']));
        $this->view->assign('form', $form);
        $this->view->assign('title', 'Andamento do Chamado');
    }

    public function editarAction() {
        $id = $this->getRequest()->getParam('id');

        $auth = Zend_Auth::getInstance();
        $user = $auth->getIdentity();

        $row = $this->_class->getById($id);
        $data = $row->toArray();

        $form = new Helpdesk_Forms_Ticket(array('level' => $user['role']['name']));
        $form->jqueryValidate(true);
        $form->jqueryTabs(false);
        $form->getElement('salvar')->setLabel('Atualizar Chamado');

        $data['prazo'] = $data['prazo']->format('d/m/Y');
        $form->populate($data);

        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();

            if ($form->isValid($post)) {
                $data = $form->getValues($post);


                $file_name = $form->getElement('arquivo')->getValue();
                $file_newname = '';
                $origem = PUBLIC_PATH . DS . 'uploads' . DS . 'docs';

                if (!empty($file_name)) {
                    $file_array = explode('.', $file_name);
                    $file_newname = md5($file_name) . rand(1, 99) . "." . end($file_array);

                    $destino = $origem . DS . $id;
                    if (!is_dir($destino)) {
                        mkdir($destino);
                    }
                    if (copy($origem . DS . $file_name, $destino . DS . $file_newname)) {
                        unlink($origem . DS . $file_name);
                    }
                    $data['arquivo'] = array('name' => $file_newname, 'real_name' => $file_name);
                } else {
                    unset($data['arquivo']);
                }

                $id = (bool) $this->_class->update($data, $id);

                if ($id) {
                    $this->_helper->getHelper('FlashMessenger')
                            ->addMessage('Chamdo atualizado com sucesso.', 'sucess');
                } else {
                    $this->_helper->getHelper('FlashMessenger')
                            ->addMessage('Não foi possível atualizar o Chamado.', 'error');
                }

                $this->_helper->redirector->goToRoute(array('action' => 'editar'));
            } else {
                $form->populate($post);
            }
        }

        $this->view->assign('form', $form);
        $this->view->assign('title', 'Chamados');
    }

    public function listarAction() {
        $auth = Zend_Auth::getInstance();
        $info = $auth->getIdentity();

        $params = array('limit'=>100);
        $role = $info['role'];
        $render = 'administrador';
        
        
        if ($role['name'] == 'cliente') {
            $params['proprietario'] = $info['user']['user_id'];
            $render = 'cliente';
            
        } else if ($role['name'] == 'samsung') {
            $params['proprietario'] = $info['user']['user_id'];
            //$params['operador_id'] = $info['user']['user_id'];
            $render = 'samsung';
            
        } else {
            if ($role['name'] == 'administrador') {
                $render = 'administrador';
                $params['cliente'] = $info['info']['cliente'];
                
            } else if ($role['name'] != 'administrador') {
                /*$params['departamento_id'] = $info['role']['role_id'];
                $render = 'departamento';*/
                $render = 'departamento';
                $params['operador_id'] = $info['user']['user_id'];
                $params['cliente'] = $info['info']['cliente'];
            } 
        }

        $params['status'] = 'em-andamento';
        $tickets = $this->_class->get($params);
        $this->view->assign('tikets', $tickets);

        $params['status'] = 'novo-chamado';
        $tickets = $this->_class->get($params);
        $this->view->assign('tikets_novos', $tickets);

        $params['status'] = 'chamado-finalizado';
        $tickets = $this->_class->get($params);
        $this->view->assign('tikets_finalizados', $tickets);

        $this->view->assign('departamento', $role);
        $this->view->assign('title', 'Chamados Ativos');
        $this->render("list/{$render}");
    }
    
    public function listarMaisAction() {
        $auth = Zend_Auth::getInstance();
        $info = $auth->getIdentity();
        $request = $this->getRequest();
        $form = new Helpdesk_Forms_BuscarDataStatus();
        
        $params = array('limit'=>300);
        $status = $request->getParam('status','em-andamento');
        $role = $info['role'];
        $render = 'administrador';
        
        
        if ($role['name'] == 'cliente') {
            $params['proprietario'] = $info['user']['user_id'];
            $render = 'cliente';
            
        } else if ($role['name'] == 'samsung') {
            $params['operador_id'] = $info['user']['user_id'];
            $render = 'samsung';
            
        } else {
            if ($role['name'] == 'administrador') {
                $render = 'administrador';
                $params['cliente'] = $info['info']['cliente'];
                
            } else if ($role['name'] != 'administrador') {
                /*$params['departamento_id'] = $info['role']['role_id'];
                $render = 'departamento';*/
                $render = 'departamento';
                $params['operador_id'] = $info['user']['user_id'];
                $params['cliente'] = $info['info']['cliente'];
            } 
        }
        
        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();

            if ($form->isValid($post)) {
                $data = $form->getValues($post);
            
                $params['start'] = $data['data_start'];
                $params['end'] = $data['data_end'];
                $params['search'] = $data['search'];
                if ($role['name'] == 'administrador') {
                    if(!empty($data['cliente'])) {
                        $params['cliente'] = $data['cliente'];
                    }
                    if(!empty($data['operador_id'])) {
                        $params['operador_id'] = $data['operador_id'];
                    }
                }
            }
        }

        $params['status'] = $status;
        $tickets = $this->_class->get($params);
        $this->view->assign('tikets', $tickets);

        $this->view->assign('form', $form);
        $this->view->assign('departamento', $role);
        $this->view->assign('title', 'Chamados');
        
        $this->render("list-more/{$render}");
        
        
    }
    
    public function autoFinalizeAction(){
        print("Finalizando automaticamente");
        $this->_class->autoFinalize();
    }

    public function apagarAction() {
        $id = $this->getRequest()->getParam('id');

        if ($this->_class->delete($id)) {
            $this->_helper->getHelper('FlashMessenger')
                    ->addMessage('Chamado apagado com sucesso.', 'sucess');
        } else {
            $this->_helper->getHelper('FlashMessenger')
                    ->addMessage('Ocorreu um erro ao tentar remover o chamado.', 'error');
        }

        $this->_helper->redirector->goToRoute(array('action' => 'listar', 'controller' => 'ticket', 'module' => 'helpdesk'), null, true);
    }

    public function finalizarAction() {
        $id = $this->getRequest()->getParam('id');
        $row = $this->_class->getById($id, 'line');
        $row->status = 'chamado-finalizado';

        if ($row->save()) {
            $auth = Zend_Auth::getInstance();
            $user = $auth->getIdentity();
            
            $andamentos = Sky_Model_Factory::getInstance()->setModule('helpdesk')->getAndamento();

            $data['ticket_id'] = $row['ticket_id'];
            $data['operador_id'] = $row['operador_id'];
            $data['proprietario'] = $user['user']['user_id'];
            $data['descricao'] = "Chamado finalizado";
            $data['status'] = "chamado-finalizado";

            $andamentos->insert($data);
            
            $this->_sendMail($row, 'chamado-finalizado', 'Chamado Finalizado');
            $this->_helper->getHelper('FlashMessenger')
                    ->addMessage('Chamado finalizado com sucesso.', 'sucess');
        } else {
            $this->_helper->getHelper('FlashMessenger')
                    ->addMessage('Ocorreu um erro ao tentar finalizar o chamado.', 'error');
        }

        $this->_helper->redirector->goToRoute(array('action' => 'andamento', 'controller' => 'ticket', 'module' => 'helpdesk', 'id' => $id), null, true);
    }

    public function reabrirAction() {
        $id = $this->getRequest()->getParam('id');
        $row = $this->_class->getById($id, 'line');
        $row->status = 'em-andamento';

        if ($row->save()) {
            
            $auth = Zend_Auth::getInstance();
            $user = $auth->getIdentity();
            
            $andamentos = Sky_Model_Factory::getInstance()->setModule('helpdesk')->getAndamento();

            $data['ticket_id'] = $row['ticket_id'];
            $data['operador_id'] = $row['operador_id'];
            $data['proprietario'] = $user['user']['user_id'];
            $data['descricao'] = "Chamado reaberto";
            $data['status'] = "em-andamento";

            $andamentos->insert($data);

            $this->_sendMail($row, 'chamado-reaberto', 'Chamado Reaberto');
            $this->_helper->getHelper('FlashMessenger')
                    ->addMessage('Chamado reaberto com sucesso.', 'sucess');
        } else {
            $this->_helper->getHelper('FlashMessenger')
                    ->addMessage('Ocorreu um erro ao tentar reabrir o chamado.', 'error');
        }

        $this->_helper->redirector->goToRoute(array('action' => 'andamento', 'controller' => 'ticket', 'module' => 'helpdesk', 'id' => $id), null, true);
    }

    protected function _sendMail($row, $template, $subject) {
        $proprietario = Sky_Model_Factory::getInstance()->setModule('admin')->getUser()->getInfoByUserId($row['proprietario']);
        $operador = Sky_Model_Factory::getInstance()->setModule('admin')->getUser()->getInfoByUserId($row['operador_id']);

        $mail = new Sky_Mail();
        $mail->setTemplate($row, $template);
        $row['assunto'] = $this->view->translate($row['assunto']);
        $mail->send("{$subject} - {$row['assunto']}", null, $proprietario['email']);

        if (count($operador) > 0) {
            $mail = new Sky_Mail();
            $mail->setTemplate($row, "{$template}-operador");
            $mail->send("{$subject} - {$row['assunto']}", null, $operador['email']);
        }
    }

}
