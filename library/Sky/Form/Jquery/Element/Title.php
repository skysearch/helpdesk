<?php
class Sky_Form_Jquery_Element_Title extends Sky_Form_Jquery_Element_Xhtml 
{
    protected $_type = 'title';
    
    public function render()
    {
        $label          = $this->getLabel();
        $id             = $this->getId();
        $class          = $this->getAttrib('class');
        $title          = $this->getAttrib('title');
        
        $html = '<h1 ';
        
        if(!empty($class)) $html .= $class;
        
       if(!empty($label)){
            $html .= 'id="'.$id.'" >'.$label.'</h1>';
        } else {
            $html .= 'id="'.$id.'" >Title</h1>';
        }   
        
        return $html;
    }
}