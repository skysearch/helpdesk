<?php
require_once 'Zend/Form/Element/Multi.php';

class Sky_Form_Jquery_Element_GoogleMaps extends Zend_Form_Element_Multi 
{
    public $helper = 'formGoogleMaps';
    
    protected $_type = "googlemaps";
    
}