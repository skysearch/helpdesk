<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

abstract class Sky_Controller_Ajax_Action extends Zend_Controller_Action 
    implements Zend_Controller_Action_Interface {
    
    /*
     * Variavel contendo um array com os contextos
     * @var array
     */
    public $contexts = array(
        'datatables'     => array('json')
    );
    
    
    public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array()) {
        parent::__construct($request, $response, $invokeArgs);
    }
    
    
    public function init() {
        $this->_helper->contextSwitch()->initContext();
        
        parent::init();
    }
}
?>
