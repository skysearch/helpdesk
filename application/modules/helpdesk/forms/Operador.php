<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Helpdesk_Forms_Operador extends Sky_Form_Jquery_Form {

    public function init() {

        
        $this->addElementPrefixPath(
                        'Admin_Forms_Validate', 
                        APPLICATION_PATH . '/modules/admin/forms/Validate/',
                        'validate');
        
        $this->addElementPrefixPath(
                        'Sky_Form_Validate', 
                         LIBRARY_PATH . '/Sky/Form/Validate/',
                        'validate');

        
        $this->setAction('')
                ->setName('operador-form')
                ->setMethod('post');

 
        $class = Sky_Model_Factory::getInstance()->setModule('helpdesk')->getOperador();
        $user = $this->createElement('select', 'operador_id', array('class' => 'required'))
                ->setLabel('Usuário')
                //->setDescription('Nível de acesso ao sistema.')
                ->setRequired(true)
                ->addMultiOptions($class->getUserComboOptions())
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty');
        
        
        $name = $this->createElement('text', 'nome')
                ->setLabel('Nome')
                //->setDescription('Nome representativo que será exibido no sistema.')
                ->addFilter('StripTags')
                ->addFilter('StringTrim');
        

        $status = $this->createElement('select', 'status', array('class' => 'required'))
                ->setLabel('Status')
                ->setRequired(true)
                ->addMultiOptions(array('1'=>'Ativo','0'=>'Inativo'))
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty');
        

        $submit = $this->createElement('button', 'salvar', array('type' => 'submit',
            'class' => '',
            'role' => 'button',
            'aria-disabled' => 'false'))
                ->setLabel('Salvar')
                ->removeDecorator('HtmlTag')
                ->removeDecorator('Label')
                ->setOrder(8);
        
        
        $this->addElements(array($user, $name, $status, $submit));
        $this->addDisplayGroup(array('operador_id','nome','status'), 'DataOperador',array('legend'=>'Dados do Operador'));
    }
    
    
}