<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Api_Forms_Nivel extends Sky_Form_Jquery_Form {

    public function init() {


        $this->addElementPrefixPath(
                        'Sky_Form_Validate', 
                         LIBRARY_PATH . '/Sky/Form/Validate/',
                        'validate');
        

        $this->setAction('')
                ->setName('nivel-form-api')
                ->setMethod('post');

        $name = $this->createElement('text', 'name', array('class' => 'required', 'role' => 'user'))
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




        $acl = Sky_Model_Factory::getInstance()->setModule('api')->getAcl();


        $resources = $acl->getResources(null, null, array('description ASC'));
        $elemens = array();

        foreach ($resources as $resource) {
            $privileges = $acl->getPrivilegesByResource($resource['module_name'], $resource['class_name']);

            $options = array();

            foreach ($privileges as $privilege) {
                $options[$privilege['privilege_id']] = $privilege['description'];
            }

            $element_name = "{$resource['module_name']}_{$resource['class_name']}";
            $this->addElement($this->createElement('MultiCheckbox', $element_name, array('clas' => 'multi-checkbox'))
                            ->setLabel($resource['description'])
                            ->addMultiOptions($options)
                            ->addFilter('Digits'));

            $elemens[] = $element_name;
        }



        $submit = $this->createElement('button', 'salvar', array('type' => 'submit',
                    'class' => '',
                    'role' => 'button',
                    'aria-disabled' => 'false'))
                ->setLabel('Salvar')
                ->removeDecorator('HtmlTag')
                ->removeDecorator('Label')
                ->setOrder(999);



        $this->addElements(array($name, $description, $submit));
        $this->addDisplayGroup(array('name', 'description'), 'DataNivel', array('legend' => 'Dados de Identificação do Nível'));
        $this->addDisplayGroup($elemens, 'PrivilegesData', array('legend' => 'Privilégios do Nível de Acesso'));
        //$this->addDisplayGroup(array('salvar'), 'ButtonAuth', array('class' => 'fieldset-buttons'));
    }

}

