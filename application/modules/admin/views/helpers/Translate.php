<?php

class Admin_View_Helper_Translate extends Zend_View_Helper_Abstract 
{
    public function translate($text=""){
        $categorias = Sky_Model_Factory::getInstance()->setModule('admin')->getCategoria();
        return $this->view->escape($categorias->translate($text));
    }
}
?>
