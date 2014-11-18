<?php
class Sky_Form_Jquery_Element_Link extends Sky_Form_Jquery_Element 
{
    protected $_type = "Link";
    
    protected $_events;

    public function render()
    {
        $html = $this->_format;
        $events = $this->_events;

        $name           = $this->getFullyQualifiedName();
        $label          = $this->getLabel();
        $id             = $this->getId();
        $value          = $this->getValue();
        $desc           = $this->getDescription();
        $type           = $this->getAttrib('type');
        $title          = $this->getAttrib('title');
        
        $class          = htmlentities($this->getAttrib('class'));
        $class         .= (!empty($class))?' ':'';
        $class         .= ($this->isRequired())?'required ':'';

        
        
        $html .= '<a id="'.$id.'" href="'.$this->_view->baseUrl($this->_view->escape($value)).'" name="'.$name.'" ';
        
        if(!empty($title)) $html .= 'title="'.$title.'" ';
        if(!empty($events)) $html .= $events.' ';
        
        $html .= 'class="'.$class.'" >';

        $html .= $this->_view->escape($label);
        
        
        $html .= '</a>';

        $html = "<li class=\"{$id}\">{$html}</li>";
        
        return $html;
    }
}