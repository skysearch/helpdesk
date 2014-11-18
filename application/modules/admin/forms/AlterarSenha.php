<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Admin_Forms_AlterarSenha extends Sky_Form_Jquery_Form {

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
                ->setName('pass-form')
                ->setMethod('post');


        $pass = $this->createElement('password', 'password', array('class' => 'required','role'=>'pass'))
                ->setLabel('Nova Senha')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('PasswordStrength');
        
        $atual = $this->createElement('password', 'atual', array('class' => 'required','role'=>'pass'))
                ->setLabel('Senha Atual')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim');
        
        $confirm = $this->createElement('password', 'confirm', array('class' => 'required','role'=>'pass'))
                ->setLabel('Confirmar Senha')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('identical',false,array('token' => 'password'));

        $hash = $this->createElement('hash','form-login-alter')
                ->setRequired(true)
                ->setLabel('Autenticidade')
                ->setOrder(4);

        $submit = $this->createElement('button', 'salvar', array('type' => 'submit',
            'class' => '',
            'role' => 'button',
            'aria-disabled' => 'false'))
                ->setLabel('Salvar')
                ->removeDecorator('HtmlTag')
                ->removeDecorator('Label')
                ->setOrder(5);

       $messages = $this->createElement('html', 'messages', array('class' => 'messages errors'));
        
        $this->addElement($hash);
        $this->addElements(array($messages,$atual, $pass, $confirm, $submit));
        $this->addDisplayGroup(array('messages','atual','password','confirm'), 'DataAuth',array('legend'=>'Alteração de Senha'));
        //$this->addDisplayGroup(array('salvar'), 'ButtonAuth',array('class'=>'fieldset-buttons'));
        
    }

}

