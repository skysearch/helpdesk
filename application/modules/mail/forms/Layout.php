<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Mail_Forms_Layout extends Sky_Form_Jquery_Form {

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
                ->setName('template-form')
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
        
        $content = $this->createElement('textarea', 'content', array('class' => 'editor','id'=>'content_mail'))
                ->setLabel('Conteúdo')
                ->setDescription('As variaveis devem ser especificadas no formato {{nome_da_variavel}}.')
                ->setRequired(true)
                ->addFilter('StringTrim');
        
        
        $submit = $this->createElement('button', 'salvar', array('type' => 'submit',
            'class' => '',
            'role' => 'button',
            'aria-disabled' => 'false'))
                ->setLabel('Salvar')
                ->removeDecorator('HtmlTag')
                ->removeDecorator('Label')
                ->setOrder(4);

        
        
        $this->addElements(array($name, $description, $content,$submit));
        $this->addDisplayGroup(array('name','description','content'), 'DataLayout',array('legend'=>'Modelo do E-mail'));
        
    }

}

