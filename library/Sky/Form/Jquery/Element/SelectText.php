<?php

class Sky_Form_Jquery_Element_SelectText extends Sky_Form_Jquery_Element_Xhtml
{
    /**
     * Default form view helper to use for rendering
     * @var string
     */
    //public $helper = 'formSelectText';
    
    public function render()
    {

        $name           = $this->getFullyQualifiedName();
        $label          = $this->getLabel();
        $id             = $this->getId();
        $value          = $this->getValue();
        $desc           = $this->getDescription();
        $text          = $this->getAttrib('text');
        $options        = $this->getAttrib('values');
        
        $class          = htmlentities($this->getAttrib('class'));
        $class         .= (!empty($class))?' ':'';


        
        $inputs = '';
        $xhtml = '';

        
        if(!empty($label)) {
            $xhtml .= "<label for=\"{$name}\">{$label}</label>";
        }
        
        $inputs = '';
        if(is_array($options)){
            $inputs = '<select name="' . $name . '" id="' . $id . '" class="' . $class . '">'."\n";
            foreach($options as $key=>$val){
                $selected = ($value==$key)?'selected="selected"':'';
                $inputs .= "<option value=\"{$key}\" {$selected}>{$val}</option>\n";
            }
            $inputs .='</select>'."\n";
        }
        
        
        if(!empty($text)){
            $xhtml .= str_replace('%s',$inputs,$text);
        }
        
        
        return "<li class=\"text {$id}\">{$xhtml}{$desc}</li>";
    }
}
