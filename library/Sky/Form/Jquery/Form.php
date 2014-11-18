<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

Abstract class Sky_Form_Jquery_Form extends Sky_Form {
    
    
    /**
     * Constructor
     *
     * @param  array|Zend_Config|null $options
     * @return void
     */
    public function __construct($options = null) {
        $this->addPrefixPath('Sky_Form_Jquery_Decorator', 'Sky/Form/Jquery/Decorator', 'decorator')
                ->addPrefixPath('Sky_Form_Jquery_Element', 'Sky/Form/Jquery/Element', 'element')
                ->addElementPrefixPath('Sky_Form_Jquery_Decorator', 'Sky/Form/Jquery/Decorator', 'decorator')
                ->addDisplayGroupPrefixPath('Sky_Form_Jquery_Form_Decorator', 'Sky/Form/Jquery/Decorator');

        parent::__construct($options);
    }
    
    
    
    /**
     * jQuery Validate
     * @var bool
     */
    protected $_jqueryValidate = false;
    
    /**
     * jQuery Validate
     * @var bool
     */
    protected $_jqueryTabs = true;
    
    
    /**
     * Params validate
     * @var string
     */
    protected $_jqueryValidateParams = '';
    
    /**
     * Params tabs
     * @var string
     */
    protected $_jqueryTabsParams = '';
    
    
    /*
     * Ability Param
     * 
     * @param bool $bool 
     */
    public function jqueryValidate($bool){
        $this->_jqueryValidate = $bool;
    }
    
    /*
     * Ability Param
     * 
     * @param bool $bool 
     */
    public function jqueryTabs($bool){
        $this->_jqueryTabs = $bool;
    }
    
    
    /*
     * Ability Param
     * 
     * @return bool $_jqueryValidate 
     */
    public function isJqueryValidate(){
        return $this->_jqueryValidate;
    }
    
    
    /*
     * Ability Param
     * 
     * @return bool $_jqueryTabs 
     */
    public function isJqueryTabs(){
        return $this->_jqueryTabs;
    }
    
    
    /*
     * Set Params
     * 
     * @param array $params 
     */
    public function setJqueryValidateParams($params){
        $this->_jqueryValidateParams = $params;
    }
    
    /*
     * Set Params
     * 
     * @param array $params 
     */
    public function setJqueryTabsParams($params){
        $this->_jqueryValidateParams = $params;
    }
    
    
    /*
     * Get Params
     * 
     * @return json $_jqueryValidateParams
     */
    public function getJqueryValidateParams(){
        return $this->_jqueryValidateParams;
    }
    
    
    /*
     * Get Params
     * 
     * @return json $_jqueryValidateParams
     */
    public function getJqueryTabsParams(){
        return $this->_jqueryValidateParams;
    }
    
    
    /**
     * Set the view object
     *
     * Ensures that the view object has the Constellation view helper path set.
     *
     * @param  Zend_View_Interface $view
     * @return PYC_Constellation_Form
     */
    public function setView(Zend_View_Interface $view = null) {
        if (null !== $view) {
            if (false === $view->getPluginLoader('helper')->getPaths('Sky_View_Helper')) {
                $view->addHelperPath('Sky/View/Helper', 'Sky_View_Helper');
            }
        }
        return parent::setView($view);
    }
    
    /**
     * Render jquery script
     *
     * @param  string form id
     * @return string
     */
    public function jqueryRender($form){
        $script = "<script type=\"text/javascript\">";
        if($this->isJqueryValidate()) {
            $params = $this->getJqueryValidateParams();
            $script .= "$(function(){";
            $script .= "$('#$form').validate(";
            if($this->_jqueryTabs) {
                $script .= "
                            { errorPlacement: function(error, element) {
                                    $('#$form').find(\"fieldset\").each( function(){
                                        if($.isError(this.id)) {
                                            $('a[href=\"#' + this.id + '\"]').addClass('error'); 
                                        } else {
                                            $('a[href=\"#' + this.id + '\"]').removeClass('error');
                                        }
                                    });

                                    error.insertAfter(element);
                                },
                                unhighlight: function(element, errorClass, validClass) {
                                    $(element).removeClass(errorClass).addClass(validClass);
                                    $(element.form).find(\"label[for=\" + element.id + \"]\")
                                                    .removeClass(errorClass);
                
                                    $('#$form').find(\"fieldset\").each( function(){
                                        if(!$.isError(this.id)) {
                                            $('a[href=\"#' + this.id + '\"]').removeClass('error'); 
                                        }
                                    });                
                                }
                             }";
            }
            $script .= ");});";
        }
        if($this->isJqueryTabs()) {
            $params = $this->getJqueryTabsParams();
            $script .= "
            $(function(){ 
            
                $('#$form.tabs ul li.tab').hide(); 
                $('#$form.tabs ul li.tab:first').show();
                $('#$form.tabs ul li.nav-tabs').show();
            
                $('#$form.tabs li.nav-tabs ul li a').click(function(){
                    $('#$form.tabs li.nav-tabs a').parent('li').removeAttr('class');
                    
                    $(this).parent('li').addClass('selected');
            
                    $.cookie('tab',$(this).attr('href'), 300);
            
                    $('#$form.tabs li.tab').hide();
                    $('#$form.tabs li.tab ' + $(this).attr('href')).parent('li.tab').show();
            
                    return false;
                })
            
            
                /*if($.cookie('tab')!=undefined){
                    tab = $.cookie('tab');
                    if($(tab).html()!=undefined){
                        $('#$form.tabs li.nav-tabs a').parent('li').removeAttr('class');
                        $('#$form.tabs li.nav-tabs a[href='+tab+']').parent('li').addClass('selected');
                        $('#$form.tabs li.tab').hide();
                        $('#$form.tabs li.tab ' + tab).parent('li.tab').show();
                    }
                }*/
            
                $('#$form.tabs').show();
            });";
        }
        $script .= "</script>";
        return  $script;         
    }
    
    
    
    

    /**
     * Render form
     *
     * @param  Zend_View_Interface $view
     * @return string
     */
    public function render(Zend_View_Interface $view = null) {
        
        if (null !== $view) {
            $this->setView($view);
        }

        $content = '';
        
        if($this->isJqueryTabs()){
            $this->setDisplayGroupDecorators(array(
                'FormElements',
                array(
                    'decorator' => array('li' => 'HtmlTag'),
                    'options' => array('tag' => 'ul')
                    ),
                'Fieldset',                
                'FormErrors',
                 
                array('HtmlTag', array('tag' => 'li','class'=>'tab')), 
                //new Zend_Form_Decorator_HtmlTag(array('tag' => 'li','class'=>'tab')),
            ));
            
            $this->setAttrib('class', 'tabs');
            $nav = '<li class="nav-tabs"><ul>';
            $i = 0;
            
            
            foreach($this->getDisplayGroups() as $grp=>$elm){
                $legend = $this->getDisplayGroup($grp)->getLegend();
                if(empty($legend))                    
                    continue;
                
                $sty = ($i==0)?' class="selected"':'';
                $nav .= "<li{$sty}><a href=\"#fieldset-$grp\"><span>{$legend}</span></a></li>";
                
                $i++;
                
            }
            $nav .= '</ul></li>';
            
            $content .= $nav;
        }
        
        foreach ($this->getDecorators() as $decorator) {
            $decorator->setElement($this);
            $content = $decorator->render($content);
        }
        
        if($this->isJqueryValidate() || $this->isJqueryTabs()) 
            $content .= $this->jqueryRender ($this->getId());
        

        $this->_setIsRendered();
        return $content;
    }
    
}

