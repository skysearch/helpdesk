<?php

class Sky_Form_SubForm extends Sky_Form {

    /**
     * Whether or not form elements are members of an array
     * @var bool
     */
    protected $_isArray = true;

    /**
     * Load the default decorators
     *
     * @return Zend_Form_SubForm
     */
    public function loadDefaultDecorators() {
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return $this;
        }

        $decorators = array(
            'FormElements',
            array(
                array('divTag' => 'HtmlTag'),
                array('tag' => 'div')
            ),
            'Form'
        );

        $this->setDecorators($decorators);

        return $this;
    }

}
