<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Admin_Forms_Recovery extends Sky_Form_Jquery_Form {

    public function init() {


        $this->_jqueryTabs = false;

        $this->setAction('')
                ->setName('recovery-form')
                ->setMethod('post');

        $login = $this->createElement('text', 'username', array('class' => 'required','role'=>'user'/*,'alt'=>'cpf'*/))
                ->setLabel('Usuário')
                ->setRequired(true)
                ->addFilter('Alnum')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty');
        
        $email = $this->createElement('text', 'email', array('class' => 'required email','role'=>'mail'))
                ->setLabel('E-mail')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('EmailAddress');
        
        $form_links = array(
            'ViewHelper',
            'Description',
            'Errors',
            array('HtmlTag', array('tag' => 'li','class'=>'form-links')),
        );
        
        $cadastre_se = $this->createElement('link', 'cadastre_se', array())
                ->setValue('admin')
                ->setLabel('Autenticação')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty')
                ->setDecorators($form_links);
        
        
        $hash = $this->createElement('hash', 'form-recovery-auth')
                ->setRequired(true);
        
        $submit = $this->createElement('button', 'entrar', array('type' => 'submit',
            'class' => '',
            'role' => 'button',
            'aria-disabled' => 'false'))
                ->setLabel('Enviar')
                ->removeDecorator('HtmlTag')
                ->removeDecorator('Label');

        $this->addElements(array($login, $email, $cadastre_se, $submit, $hash));


        $this->addDisplayGroup(array('username','email','cadastre_se','entrar'), 'DataAuth');
        //$this->addDisplayGroup(array('entrar'), 'ButtonAuth',array('class'=>'fieldset-buttons'));
        
        
        
        $this->addDecorator('FormElements')
                ->addDecorator('HtmlTag', array('tag' => 'ul'))
                ->addDecorator('Form');
        
        $this->setElementDecorators(array(
            'ViewHelper',
            'Label',
            'Errors',
            new Zend_Form_Decorator_HtmlTag(array('tag' => 'li'))
        ));
        
        $this->setDisplayGroupDecorators(array(
            'FormElements',
            'Fieldset',
            'FormErrors',
            new Zend_Form_Decorator_HtmlTag(array('tag' => 'li')),
        ));
    }

}

