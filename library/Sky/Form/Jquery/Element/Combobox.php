<?php
require_once 'Zend/Form/Element/Multi.php';

class Sky_Form_Jquery_Element_Combobox extends Zend_Form_Element_Multi 
{
    public $helper = 'formCombobox';
    
    protected $_type = "combobox";
    
}