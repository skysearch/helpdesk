<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Admin_Forms_Config extends Sky_Form_Jquery_Form {

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
                ->setName('config-form')
                ->setMethod('post');

        
        
        $configs = Sky_Model_Factory::getInstance()->setModule('admin')->getConfig();
        $rows = $configs->getAll();
        $elements = array();
        
        foreach ($rows as $row) {
            $elements[$row['module']][$row['param']] = $this->createElement($row['type'], $row['param'])
                         ->setLabel($row['label'])
                         ->setValue($row['value'])
                         ->setOrder($row['order'])
                         ->addFilters(explode(';', $row['filters']))
                         ->addValidators(explode(';', $row['validations']));
            
            if(!empty($row['descriptions'])) {
                $elements[$row['module']][$row['param']]->setDescription($row['descriptions']);
            }
            
            if(!empty($row['source']) && !is_null($row['source'])) {
                if($row['source']=='array') {
                    $options = explode(',', $row['options']);
                    foreach ($options as $opt) {
                        $opc = explode(':', $opt);
                        $params[$row['param']][$opc[0]] = end($opc); 
                    }
                    
                } else if($row['source']=='category') { 
                    $name = "Categoria";
                    $module = "Admin";
                    
                    eval("\$class = Sky_Model_Factory::getInstance()->setModule('{$module}')->get{$name}();");
                    $params = explode(',', $row['options']);
                    $params[$row['param']] = $class->getComboOptions($params[0],$params[1],$params[2]);
                    
                } else {
                    $name = ucfirst($row['source']);
                    $module = $row['module'];
                    $exp = explode('_', $name);
                    
                    if(count($exp)>0) {
                        $module = $exp[0];
                        $name = end($exp);
                    }
                    eval("\$class = Sky_Model_Factory::getInstance()->setModule('{$module}')->get{$name}();");
                    $params = explode(',', $row['options']);
                    $params[$row['param']] = $class->getComboOptions($params[0],end($params));
                }
                
                $elements[$row['module']][$row['param']]->addMultiOptions($params[$row['param']]);
            }
        }
        
        foreach ($elements as $k=>$v){
            $this->addElements($v);
            $this->addDisplayGroup(array_keys($v), "Data{$k}",array('legend'=>ucfirst($k)));
        }
        
        
        $submit = $this->createElement('button', 'salvar', array('type' => 'submit',
            'class' => '',
            'role' => 'button',
            'aria-disabled' => 'false'))
                ->setLabel('Salvar')
                ->removeDecorator('HtmlTag')
                ->removeDecorator('Label')
                ->setOrder(8);

        
        
        $this->addElement($submit);

    }

}

