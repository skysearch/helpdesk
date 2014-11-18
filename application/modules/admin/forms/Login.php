<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Admin_Forms_Login extends Sky_Form_Jquery_Form {

    public function init() {


        $this->_jqueryTabs = false;

        $this->setAction('')
                ->setName('login-form')
                ->setMethod('post');

        $login = $this->createElement('text', 'username', array('class' => 'required','role'=>'user'/*,'alt'=>'cpf'*/))
                ->setLabel('Usuário')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty');

        $pass = $this->createElement('password', 'password', array('class' => 'required','role'=>'pass'))
                ->setLabel('Senha')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty');
        
        
        $form_links = array(
            'ViewHelper',
            'Description',
            'Errors',
            array('HtmlTag', array('tag' => 'li','class'=>'form-links')),
        );
        
        
        $lembrar = $this->createElement('link', 'lembrar', array())
                ->setValue('admin/auth/recovery')
                ->setLabel('Lembrar senha')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty')
                ->setDecorators($form_links);

        $hash = $this->createElement('hash', 'form-login-auth')
                ->setRequired(true);
        
        $submit = $this->createElement('button', 'entrar', array('type' => 'submit',
            'class' => '',
            'role' => 'button',
            'aria-disabled' => 'false'))
                ->setLabel('Entrar')
                ->removeDecorator('HtmlTag')
                ->removeDecorator('Label');

        $messages = $this->createElement('html', 'messages', array('class' => 'messages errors'));
        $this->addElements(array($messages, $login, $pass, $lembrar, $submit, $hash));


        $this->addDisplayGroup(array('messages','username','password','lembrar','entrar'), 'DataAuth');
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

