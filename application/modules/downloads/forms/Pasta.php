<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Downloads_Forms_Pasta extends Sky_Form_Jquery_Form {

    public function init() {

        $categoria = Sky_Model_Factory::getInstance()->setModule('admin')->getCategoria();
        
        $this->addElementPrefixPath(
                        'Admin_Forms_Validate', 
                        APPLICATION_PATH . '/modules/admin/forms/Validate/',
                        'validate');
        
        $this->addElementPrefixPath(
                        'Sky_Form_Validate', 
                         LIBRARY_PATH . '/Sky/Form/Validate/',
                        'validate');

        
        $this->setAction('')
                ->setName('pasta-form')
                ->setMethod('post')
                ->setAttrib('enctype', 'multipart/form-data');

        
        /*$departamentos = Sky_Model_Factory::getInstance()->setModule('downloads')->getDepartamento();
        $options = $departamentos->getComboOptions();
        
        array_shift($options);
        
        $departamento = $this->createElement('multiCheckbox', 'departamento_id', array('class' => 'required'))
                //->setLabel('Departamento')
                //->setDescription('Nível de acesso ao sistema.')
                ->setRequired(true)
                ->addMultiOptions($options)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty');*/
        
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
        
        $assunto = $this->createElement('text', 'nome', array('class' => 'required'))
                ->setLabel('Nome')
                ->setDescription('Nome da pasta.')
                ->addFilter('StripTags')
                ->addFilter('StringTrim');
        
        $descricao = $this->createElement('textarea', 'descricao', array('class' => 'required','rows'=>5))
                ->setLabel('Descrição')
                //->setDescription('Nome representativo que será exibido no sistema.')
                ->addFilter('StripTags')
                ->addFilter('StringTrim');
        
        
        $submit = $this->createElement('button', 'salvar', array('type' => 'submit',
            'class' => '',
            'role' => 'button',
            'aria-disabled' => 'false'))
                ->setLabel('Criar Pasta')
                ->removeDecorator('HtmlTag')
                ->removeDecorator('Label')
                ->setOrder(9);
        
        $this->addElements(array($grupo, $assunto, $descricao, $submit));
        $this->addDisplayGroup(array('grupo'), 'DataDepartamento',array('legend'=>'Para quem se destina essa pasta?'));
        $this->addDisplayGroup(array('nome','descricao'), 'DataPasta',array('legend'=>'Informações da Pasta'));
            
    }
    
    
}