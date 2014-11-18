<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Helpdesk_Forms_Departamento extends Sky_Form_Jquery_Form {

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
                ->setName('dep-form')
                ->setMethod('post');

        
 
        $class = Sky_Model_Factory::getInstance()->setModule('helpdesk')->getDepartamento();
        $user = $this->createElement('select', 'departamento_id', array('class' => 'required'))
                ->setLabel('Nível')
                //->setDescription('Nível de acesso ao sistema.')
                ->setRequired(true)
                ->addMultiOptions($class->getRoleComboOptions())
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
        $this->addDisplayGroup(array('departamento_id','nome','status'), 'DataOperador',array('legend'=>'Dados do Departamento'));
    }
    
    
}