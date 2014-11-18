<?php
class Sky_Form_Jquery_Element_FacebookPicture extends Sky_Form_Jquery_Element 
{
    protected $_type = "checkbox";
    
 
    public function render()
    {
        $html = $this->_format;

        $name           = $this->getFullyQualifiedName();
        $label          = $this->getLabel();
        $id             = $this->getId();
        $desc           = $this->getDescription();
        $title          = $this->getAttrib('title');
        
        $class          = htmlentities($this->getAttrib('class'));
        $class         .= (!empty($class))?' ':'';
        $class         .= ($this->isRequired())?'required ':'';

        
        $html .= '<input id="'.$id.'" type="hidden" name="'.$name.'" ';
        
        if(!empty($title)) $html .= 'title="'.$title.'" ';
        
        $html .= 'class="'.$class.'" />';
        
        $image = $this->_view->facebookInfo();
        $auth = $this->_view->facebookAuth($id);

        $html = "<li class=\"facebook-picture {$id}\">
                    <label class=\"label\">{$html} {$label}</label>
                    <p class=\"description\">{$desc}</p>
                    
                    {$auth}
                    {$image}
                 </li>";
        
        return $html;
    }
}