<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Admin_Forms_Categoria extends Sky_Form_Jquery_Form {

    public function init() {

        $class = Sky_Model_Factory::getInstance()->setModule('admin')->getCategoria();
        
        $this->addElementPrefixPath(
                        'Admin_Forms_Validate', 
                        APPLICATION_PATH . '/modules/admin/forms/Validate/',
                        'validate');
        
        $this->addElementPrefixPath(
                        'Sky_Form_Validate', 
                         LIBRARY_PATH . '/Sky/Form/Validate/',
                        'validate');
        
        $this->setAction('')
                ->setName('categoria-form')
                ->setMethod('post');

        $name = $this->createElement('text', 'name', array('class' => 'required'))
                ->setLabel('Nome')
                ->setDescription('Nome que será utilizado pelo sistema (deve ser único).')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim');
        
        $description = $this->createElement('text', 'description', array('class' => 'required'))
                ->setLabel('Descrição')
                ->setDescription('Nome representativo que será exibido no sistema.')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim');
        
        $filter = $this->createElement('select', 'filter', array('class' => 'required'))
                ->setLabel('Filtro')
                ->setRequired(true)
                ->addMultiOptions($class->getComboOptions('filter'))
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty');
        
        $parent = $this->createElement('Combobox', 'parent', array())
                ->setLabel('Parent')
                ->addMultiOptions($class->getComboOptions())
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty');
        
        $order = $this->createElement('text', 'order', array('alt'=>'integer','role'=>'integer'))
                ->setLabel('Ordem')
                ->setDescription('Digite um valor de 1 a 99.')
                ->setValue(0)
                ->addFilter('Digits')
                ->addFilter('StripTags')
                ->addFilter('StringTrim');
        
        
        $extra = $this->createElement('textarea', 'extra', array())
                ->setLabel('Extra')
                ->addFilter('StringTrim');
        
        $submit = $this->createElement('button', 'salvar', array('type' => 'submit',
            'class' => '',
            'role' => 'button',
            'aria-disabled' => 'false'))
                ->setLabel('Salvar')
                ->removeDecorator('HtmlTag')
                ->removeDecorator('Label')
                ->setOrder(8);

        
        $this->addElements(
                array($filter,
                        $name,
                        $description,
                        $parent,
                        $extra,
                        $order,
                        $submit));
        
        $this->addDisplayGroup(array('filter',
                        'name',
                        'description',
                        'extra',
                        'parent',
                        'order'
                        ), 'DataCategoria',array('legend'=>'1 - Dados da Categoria'));
        
    }

}

