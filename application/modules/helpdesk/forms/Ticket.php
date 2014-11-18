<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Helpdesk_Forms_Ticket extends Sky_Form_Jquery_Form {

    protected $_level;

    const FORMS_OPERATOR_MASTER = 'administrador';
    const FORMS_OPERATOR_CLIENTE = 'cliente';
    const FORMS_OPERATOR_SAMSUNG = 'samsung';
    const FORMS_OPERATOR_ASM = 'asm';

    public function __construct($options = null) {


        if (key_exists('level', $options)) {
            $this->_level = $options['level'];
        }

        parent::__construct($options);
    }

    public function init() {

        $categoria = Sky_Model_Factory::getInstance()->setModule('admin')->getCategoria();
        $configs = Sky_Module_Config::getTable('helpdesk');

        $this->addElementPrefixPath(
                'Admin_Forms_Validate', APPLICATION_PATH . '/modules/admin/forms/Validate/', 'validate');

        $this->addElementPrefixPath(
                'Sky_Form_Validate', LIBRARY_PATH . '/Sky/Form/Validate/', 'validate');


        $this->setAction('')
                ->setName('ticket-form')
                ->setMethod('post')
                ->setAttrib('enctype', 'multipart/form-data');


        $operadores = Sky_Model_Factory::getInstance()->setModule('helpdesk')->getOperador();
        $operador = $this->createElement('select', 'operador_id', array('class' => 'required'))
                ->setLabel('Operador')
                //->setDescription('Nível de acesso ao sistema.')
                ->setRequired(true)
                ->addMultiOptions($operadores->getUserComboOptions())
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty');

        /* $departamentos = Sky_Model_Factory::getInstance()->setModule('helpdesk')->getDepartamento();
          $departamento = $this->createElement('select', 'departamento_id', array('class' => 'required'))
          ->setLabel('Departamento')
          //->setDescription('Nível de acesso ao sistema.')
          ->setRequired(true)
          ->addMultiOptions($departamentos->getComboOptions())
          ->addFilter('StripTags')
          ->addFilter('StringTrim')
          ->addValidator('NotEmpty'); */


        $options = $categoria->getComboOptions('assunto');
        array_shift($options);

        $assunto = $this->createElement('select', 'assunto', array('class' => 'required'))
                ->setLabel('Assunto')
                ->setRequired(true)
                ->addMultiOptions($options)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty');





        $departamentos = Sky_Model_Factory::getInstance()->setModule('helpdesk')->getDepartamento();
        $departamento = $this->createElement('select', 'departamento_id', array('class' => 'required'))
                ->setLabel('Departamento')
                //->setDescription('Nível de acesso ao sistema.')
                ->setRequired(true)
                ->addMultiOptions($departamentos->getComboOptions())
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty');


        $status = $this->createElement('select', 'status', array('class' => 'required'))
                ->setLabel('Status')
                //->setDescription('Nível de acesso ao sistema.')
                ->setRequired(true)
                ->addMultiOptions($categoria->getComboOptions('status'))
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty')
                ->setValue($configs['helpdesk_status']);

        $priority = $this->createElement('select', 'prioridade', array('class' => 'required'))
                ->setLabel('Prioridade')
                //->setDescription('Nível de acesso ao sistema.')
                ->setRequired(true)
                ->addMultiOptions($categoria->getComboOptions('prioridade'))
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty')
                ->setValue($configs['helpdesk_priority']);

        $prazo = $this->createElement('text', 'prazo', array('class' => 'required dateBR', 'role' => 'date', 'alt' => 'date'))
                ->setLabel('Prazo')
                //->setDescription('Nome representativo que será exibido no sistema.')
                ->addFilter('StripTags')
                ->addFilter('StringTrim');

        /* $assunto = $this->createElement('text', 'assunto', array('class' => 'required'))
          ->setLabel('Assunto')
          //->setDescription('Nome representativo que será exibido no sistema.')
          ->addFilter('StripTags')
          ->addFilter('StringTrim'); */

        $descricao = $this->createElement('textarea', 'descricao', array('class' => 'required'))
                ->setLabel('Descrição')
                //->setDescription('Nome representativo que será exibido no sistema.')
                ->addFilter('StripTags')
                ->addFilter('StringTrim');

        $file = $this->createElement('file', 'arquivo', array())
                ->setLabel('<i class="fa fa-share-square-o"></i> ')
                ->setDestination(PUBLIC_PATH . DS . 'uploads' . DS . 'docs')
                ->addValidator('ExcludeExtension', false, array('php', 'exe', 'bin', 'src', 'py'));

        $submit = $this->createElement('button', 'salvar', array('type' => 'submit',
                    'class' => '',
                    'role' => 'button',
                    'aria-disabled' => 'false'))
                ->setLabel('Criar Chamado')
                ->removeDecorator('HtmlTag')
                ->removeDecorator('Label')
                ->setOrder(8);

        if ($this->_level == self::FORMS_OPERATOR_MASTER) {
            $options = $categoria->getComboOptions('cliente');
            array_shift($options);

            $cliente = $this->createElement('select', 'cliente', array('class' => 'required'))
                    ->setLabel('Cliente')
                    ->setRequired(true)
                    ->addMultiOptions($options)
                    ->addFilter('StripTags')
                    ->addFilter('StringTrim')
                    ->addValidator('NotEmpty');

            $this->addElements(array($operador, $cliente, /* $prazo, */ $priority, $status, $assunto, $descricao, $file, $submit));
            $this->addDisplayGroup(array('operador_id', 'cliente', /* 'prazo', */ 'prioridade', 'status'), 'DataOperador', array('legend' => 'Propriedades'));
            $this->addDisplayGroup(array('assunto', 'descricao', 'arquivo'), 'DataTicket', array('legend' => 'Informações do Chamado'));
        } else if ($this->_level == self::FORMS_OPERATOR_CLIENTE) {
            $this->addElements(array(/* $departamento, */$assunto, $descricao, $file, $submit));
            $this->addDisplayGroup(array(/* 'departamento_id', */'assunto', 'descricao', 'arquivo'), 'DataTicket', array('legend' => 'Informações do Chamado'));
            
        } else if ($this->_level == self::FORMS_OPERATOR_SAMSUNG || $this->_level == self::FORMS_OPERATOR_ASM) {
            $auth = Zend_Auth::getInstance();
            $info = $auth->getIdentity();
            
            $options = array();
            foreach($info['info']['cliente'] as $c){
                $options[$c] = $categoria->translate($c);
            }

            $cliente = $this->createElement('select', 'cliente', array('class' => 'required'))
                    ->setLabel('Cliente')
                    ->setRequired(true)
                    ->addMultiOptions($options)
                    ->addFilter('StripTags')
                    ->addFilter('StringTrim')
                    ->addValidator('NotEmpty');

            $this->addElements(array($cliente, /* $prazo,  $priority, $status,*/ $assunto, $descricao, $file, $submit));
            $this->addDisplayGroup(array('cliente', /* 'prazo',  'prioridade', 'status'*/), 'DataOperador', array('legend' => 'Propriedades'));
            $this->addDisplayGroup(array('assunto', 'descricao', 'arquivo'), 'DataTicket', array('legend' => 'Informações do Chamado'));
            
        } else {    
            $auth = Zend_Auth::getInstance();
            $info = $auth->getIdentity();
            
            $options = array();
            foreach($info['info']['cliente'] as $c){
                $options[$c] = $categoria->translate($c);
            }

            $cliente = $this->createElement('select', 'cliente', array('class' => 'required'))
                    ->setLabel('Cliente')
                    ->setRequired(true)
                    ->addMultiOptions($options)
                    ->addFilter('StripTags')
                    ->addFilter('StringTrim')
                    ->addValidator('NotEmpty');

            $this->addElements(array($cliente, /* $prazo, */ $priority, $status, $assunto, $descricao, $file, $submit));
            $this->addDisplayGroup(array('cliente', /* 'prazo', */ 'prioridade', 'status'), 'DataOperador', array('legend' => 'Propriedades'));
            $this->addDisplayGroup(array('assunto', 'descricao', 'arquivo'), 'DataTicket', array('legend' => 'Informações do Chamado'));
        }
    }

}
