<?php
class Sky_Form_Jquery_Element_Button extends Sky_Form_Jquery_Element 
{
    protected $_type = "button";
    
    protected $_events;


    public function setEvent($event,$action){
        $this->_events .= $event.'="'.$action.'"';
        return $this;
    }
    
    public function setEvents(array $events){
        foreach($events as $event=>$action){
            $this->setEvent($event, $action);
        }
        return $this;
    }

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

        
        $html .= '<button id="'.$id.'" type="'.$type.'" name="'.$name.'" ';
        
        if(!empty($title)) $html .= 'title="'.$title.'" ';
        if(!empty($events)) $html .= $events.' ';
        
        $html .= 'class="'.$class.'" >';

        if(!empty($value)) $html .= $value;
        else if(!empty($label)) $html .= $label;
        
        $html .= '</button>';

        $html = "<li class=\"button {$id}\">{$html}</li>";
        
        return $html;
    }
}