<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Helpdesk_Forms_BuscarDataStatus extends Sky_Form_Jquery_Form {

    public function init() {

        $this->_jqueryTabs = false;

        $categoria = Sky_Model_Factory::getInstance()->setModule('admin')->getCategoria();
        
        $this->addElementPrefixPath(
                'Admin_Forms_Validate', APPLICATION_PATH . '/modules/admin/forms/Validate/', 'validate');

        $this->setAction('')
                ->setName('buscar-ano-mes-form')
                ->setMethod('post');

        /*$search = $this->createElement('text', 'search', array('role' => 'search'))
                ->setLabel('Buscar por')
                ->addFilter('StripTags')
                ->addFilter('StringTrim');*/
        
        $options = $categoria->getComboOptions('assunto');
        array_shift($options);
        $options[''] = '';
        
        $search = $this->createElement('combobox', 'search', array('class' => 'required'))
                ->setLabel('Assunto')
                ->addMultiOptions($options)
                ->addFilter('StripTags')
                ->addFilter('StringTrim');


        $start = $this->createElement('text', 'data_start', array('role' => 'date', 'alt' => 'date', 'class' => 'size-1','placeholder'=>'Data inicial da solicitação'))
                ->setLabel('Data Inicial')
                ->addFilter('StripTags')
                ->addFilter('StringTrim');

        $end = $this->createElement('text', 'data_end', array('role' => 'date', 'alt' => 'date', 'class' => 'size-1','placeholder'=>'Data final da solicitação'))
                ->setLabel('Data Final')
                ->addFilter('StripTags')
                ->addFilter('StringTrim');


        $operadores = Sky_Model_Factory::getInstance()->setModule('helpdesk')->getOperador();
        $operador = $this->createElement('combobox', 'operador_id', array('class' => 'required','placeholder'=>'Operador'))
                ->setLabel('Operador')
                ->addMultiOptions($operadores->getUserComboOptions())
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty');


        $auth = Zend_Auth::getInstance();
        $info = $auth->getIdentity();

        $options = array(''=>'');
        foreach ($info['info']['cliente'] as $c) {
            $options[$c] = $categoria->translate($c);
        }

        $cliente = $this->createElement('combobox', 'cliente', array('class' => 'required','placeholder'=>'Cliente'))
                ->setLabel('Cliente')
                ->addMultiOptions($options)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty');

        /* $status = $this->createElement('combobox', 'status', array('class'=>'size-1'))
          ->setLabel('Situação')
          ->addMultiOptions(array(1=>'Pago',0=>'Pendente'))
          ->addFilter('StripTags')
          ->addFilter('StringTrim')
          ->addValidator('NotEmpty'); */


        $submit = $this->createElement('button', 'submit', array('type' => 'submit',
                    'class' => 'bt-search',
                    'role' => 'button',
                    'aria-disabled' => 'false'))
                ->setLabel('Buscar')
                ->removeDecorator('HtmlTag')
                ->removeDecorator('Label');


        if($info['role']['name'] == Helpdesk_Forms_Ticket::FORMS_OPERATOR_MASTER){
            $this->addElements(
                    array(
                        $search,
                        $start,
                        $end,
                        $operador,
                        $cliente,
                        $submit));

            $this->addDisplayGroup(array('search', 'data_start', 'data_end', 'operador_id', 'cliente', 'submit'), 'DataSearch');
        } else if($info['role']['name'] == Helpdesk_Forms_Ticket::FORMS_OPERATOR_SAMSUNG || $info['role']['name'] == Helpdesk_Forms_Ticket::FORMS_OPERATOR_ASM) {
            $this->addElements(
                    array(
                        $search,
                        $start,
                        $end,
                        $cliente,
                        $submit));

            $this->addDisplayGroup(array('search', 'data_start', 'data_end', 'cliente', 'submit'), 'DataSearch');
            
        } else if($info['role']['name'] == Helpdesk_Forms_Ticket::FORMS_OPERATOR_CLIENTE) {
            
            $this->addElements(
                    array(
                        $search,
                        $start,
                        $end,
                        $submit));

            $this->addDisplayGroup(array('search', 'data_start', 'data_end', 'submit'), 'DataSearch');
        }
    }

}
