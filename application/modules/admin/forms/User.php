<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Admin_Forms_User extends Sky_Form_Jquery_Form {

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
                ->setName('user-form')
                ->setMethod('post');

        $name = $this->createElement('text', 'name', array('class' => 'required'))
                ->setLabel('Nome')
                ->setDescription('Nome representativo que será exibido no sistema.')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim');
        
        $email = $this->createElement('text', 'email', array('class' => 'required email','role'=>'mail'))
                ->setLabel('E-mail')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('EmailAddress');
        
        $login = $this->createElement('text', 'username', array('class' => 'required','role'=>'user'))
                ->setLabel('Usuário')
                ->setDescription('Nome que será utilizado pelo sistema (deve ser único).')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addFilter('StringToLower')
                ->addValidator('stringLength', false, array(5));

        $pass = $this->createElement('password', 'password', array('class' => 'required','role'=>'pass'))
                ->setLabel('Senha')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim');
        

        $status = $this->createElement('select', 'status', array('class' => 'required'))
                ->setLabel('Status')
                ->setRequired(true)
                ->addMultiOptions(array('1'=>'Ativo','0'=>'Inativo'))
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty');
        
        
        $roles = Sky_Model_Factory::getInstance()->setModule('admin')->getAcl();
        $roles = $roles->getRoles();
        $options = array();
        foreach ($roles as $r) $options[$r['role_id']] = $r['description']; 
        
        
        $role = $this->createElement('select', 'role_id', array('class' => 'required'))
                ->setLabel('Nível')
                ->setDescription('Nível de acesso ao sistema.')
                ->setRequired(true)
                ->addMultiOptions($options)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty');
        
        
        $class = Sky_Model_Factory::getInstance()->setModule('admin')->getCategoria();
        $options = $class->getComboOptions('grupos');
        array_shift($options);
        
        $grupo = $this->createElement('multiCheckbox', 'grupo', array('class' => 'required'))
                //->setLabel('Departamento')
                //->setDescription('Nível de acesso ao sistema.')
                ->setRequired(true)
                ->addMultiOptions($options)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty');
        
        
        $class = Sky_Model_Factory::getInstance()->setModule('admin')->getCategoria();
        $options = $class->getComboOptions('cliente');
        array_shift($options);
        
        $cliente = $this->createElement('multiCheckbox', 'cliente', array('class' => 'required'))
                //->setLabel('Departamento')
                //->setDescription('Nível de acesso ao sistema.')
                ->setRequired(true)
                ->addMultiOptions($options)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty');
        
        
        
        $hash = $this->createElement('hash','form-user-alter')
                ->setRequired(true)
                ->setLabel('Autenticidade')
                ->setOrder(7);
        
        $submit = $this->createElement('button', 'salvar', array('type' => 'submit',
            'class' => '',
            'role' => 'button',
            'aria-disabled' => 'false'))
                ->setLabel('Salvar')
                ->removeDecorator('HtmlTag')
                ->removeDecorator('Label')
                ->setOrder(8);
        
        
        $this->addElements(array($name, $email, $login, $pass, $status, $role, $grupo, $cliente, $submit, $hash));
        $this->addDisplayGroup(array('username','password','role_id','status'), 'DataAuthUser',array('legend'=>'Dados de Acesso'));
        $this->addDisplayGroup(array('name','email'), 'DataUser',array('legend'=>'Informações do Usuário'));   
        $this->addDisplayGroup(array('grupo'), 'DataGrupo',array('legend'=>'Grupo de Usuário')); 
        $this->addDisplayGroup(array('cliente'), 'DataCliente',array('legend'=>'Cliente do Usuário'));
    }
    
    
}