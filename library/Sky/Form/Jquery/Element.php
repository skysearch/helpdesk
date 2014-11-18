<?php
/**
 * Base Form Element for Constellation View Helpers
 *
 * @package    PYC_Constellation
 * @subpackage Form
 */
class Sky_Form_Jquery_Element extends Zend_Form_Element {
    
    /**
     * Just here to prevent errors.
     *
     * @var array
     */
    public $options = array();
    
    protected $_format;
    
    /**
     * Constructor
     *
     * @param  mixed $spec
     * @param  mixed $options
     * @return void
     */
    public function __construct($spec, $options = null)
    {
        parent::__construct($spec, $options);
    }
    
    /**
     * Set the view object
     *
     * Ensures that the view object has the Constellation view helper path set.
     *
     * @param  Zend_View_Interface $view
     */
    public function setView(Zend_View_Interface $view = null)
    {
        if (null !== $view) {
            if (false === $view->getPluginLoader('helper')->getPaths('Sky_View_Helper')) {
                $view->addHelperPath('Sky/View/Helper', 'Sky_View_Helper');
            }
        }
        return parent::setView($view);
    }

}