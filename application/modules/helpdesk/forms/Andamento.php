<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Helpdesk_Forms_Andamento extends Sky_Form_Jquery_Form {

    protected $_level;
    const FORMS_OPERATOR_MASTER = 'administrador';
    const FORMS_OPERATOR_CLIENTE = 'cliente';

    public function __construct($options = null) {
        
        
        if(key_exists('level', $options)) {
            $this->_level = $options['level'];
        }
        
        parent::__construct($options);
    }

    public function init() {

        $configs = Sky_Module_Config::getTable('helpdesk');
        
        $this->addElementPrefixPath(
                        'Admin_Forms_Validate', 
                        APPLICATION_PATH . '/modules/admin/forms/Validate/',
                        'validate');
        
        $this->addElementPrefixPath(
                        'Sky_Form_Validate', 
                         LIBRARY_PATH . '/Sky/Form/Validate/',
                        'validate');

        
        $this->setAction('')
                ->setName('andamento-form')
                ->setMethod('post');

        
        $descricao = $this->createElement('textarea', 'descricao', array('class' => 'required'))
                ->setLabel('Descrição')
                //->setDescription('Nome representativo que será exibido no sistema.')
                ->addFilter('StripTags')
                ->addFilter('StringTrim');
        
        
        $file = $this->createElement('file', 'arquivo', array())
                ->setLabel('<i class="fa fa-share-square-o"></i> ')
                ->setDestination(PUBLIC_PATH.DS.'uploads'.DS.'docs')
                ->addValidator('ExcludeExtension', false, array('php', 'exe', 'bin', 'src', 'py'));
        
        $submit = $this->createElement('button', 'responder', array('type' => 'submit',
            'class' => '',
            'role' => 'button',
            'aria-disabled' => 'false'))
                ->setLabel('<i class="fa fa-comments-o"></i> Enviar Resposta')
                ->removeDecorator('HtmlTag')
                ->removeDecorator('Label');
        
        if($this->_level==self::FORMS_OPERATOR_MASTER) {
            $this->addElements(array($descricao, $file, $submit));
            $this->addDisplayGroup(array('descricao','arquivo','responder'), 'DataTicket');
            
        } else if($this->_level==self::FORMS_OPERATOR_CLIENTE) {
            $this->addElements(array($descricao, $file, $submit));
            $this->addDisplayGroup(array('descricao','arquivo','responder'), 'DataTicket');
            
        } else {
            $this->addElements(array($descricao, $file, $submit));
            $this->addDisplayGroup(array('descricao','arquivo','responder'), 'DataTicket');
            
        }
    }
    
}