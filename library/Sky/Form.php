<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

Abstract class Sky_Form extends Zend_Form {
    
    
    
    
    /**
     * Load the default decorators
     *
     * @return void
     */
    public function loadDefaultDecorators()
    {
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return;
        }

        $decorators = $this->getDecorators();
        
        if (empty($decorators)) {
            $this->addDecorator('FormElements')
                    ->addDecorator('HtmlTag', array('tag' => 'ul'))
                    ->addDecorator('Form');
        }
        
        
        $this->setElementDecorators(array(
            'ViewHelper',
            'Label',
            'Description',
            'Errors',
            new Zend_Form_Decorator_HtmlTag(array('tag' => 'li'))
        ));
        
        
        
        $this->setDisplayGroupDecorators(array(
            'FormElements',
            'Fieldset',
            'FormErrors',
            new Zend_Form_Decorator_HtmlTag(array('tag' => 'li')),
        ));
        
        return $this;
    }

}

