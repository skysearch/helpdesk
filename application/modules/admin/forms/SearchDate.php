<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Admin_Forms_SearchDate extends Sky_Form_Jquery_Form {

    public function init() {

        $this->_jqueryTabs = false;
        
        $this->addElementPrefixPath(
                        'Admin_Forms_Validate', 
                        APPLICATION_PATH . '/modules/admin/forms/Validate/',
                        'validate');
        
        $this->setAction('')
                ->setName('buscar-mostruario-form')
                ->setMethod('post');

        
        $start = $this->createElement('text', 'data_start', array('role'=>'date','alt'=>'date','class'=>'size-1'))
                ->setLabel('Data Inicial')
                ->addFilter('StripTags')
                ->addFilter('StringTrim');
        
        $end = $this->createElement('text', 'data_end', array('role'=>'date','alt'=>'date','class'=>'size-1'))
                ->setLabel('Data Final')
                ->addFilter('StripTags')
                ->addFilter('StringTrim');
        

        $search = $this->createElement('text', 'search', array('role'=>'search'))
                ->setLabel('Palavra Chave')
                ->addFilter('StripTags')
                ->addFilter('StringTrim');

        
        
        $submit = $this->createElement('button', 'submit', array('type' => 'submit',
            'class' => 'bt-search',
            'role' => 'button',
            'aria-disabled' => 'false'))
                ->setLabel('Buscar')
                ->removeDecorator('HtmlTag')
                ->removeDecorator('Label');

        
        
        $this->addElements(
                array(
                    $start,
                    $end,
                    $search,
                    $submit));
        
        $this->addDisplayGroup(array('search','data_start','data_end','submit'), 'DataSearch');
        
    }

}

