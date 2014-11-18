<?php

/**
 * Zend_Form_Element
 *
 * @category   Zend
 * @package    Zend_Form
 * @subpackage Element
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: File.php 23871 2011-04-23 22:40:16Z ramon $
 */
class Sky_Form_Jquery_Element_File extends Zend_Form_Element_File
{
    
    protected $_type = "file";

    /**
     * Render form element
     * Checks for decorator interface to prevent errors
     *
     */
        public function render()
    {
        $html = '';

        $name           = $this->getFullyQualifiedName();
        $label          = $this->getLabel();
        $id             = $this->getId();
        $desc           = $this->getDescription();
        $title          = $this->getAttrib('title');
        $maxfile        = $this->getMaxFileSize();
        $messages       = $this->getMessages();
        $errors         = '';
        
        if(count($messages)>0){
            foreach ($messages as $error){
                $errors .= "<label class=\"error\">{$error}</label>";
            }
        } 
        
        $class          = htmlentities($this->getAttrib('class'));
        $class         .= (!empty($class))?' ':'';
        $class         .= ($this->isRequired())?'required ':'';

        
        $html .= '<input id="'.$id.'" type="file" name="'.$name.'" ';
        
        if(!empty($title)) $html .= 'title="'.$title.'" ';
        
        $html .= 'class="'.$class.'" />';
        

        $html = "<li class=\"facebook-picture {$id}\">
                    <label class=\"label\">{$label}</label>
                    {$html}
                    <input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"{$maxfile}\" id=\"MAX_FILE_SIZE\">
                    {$errors}
                    <p class=\"hint\">{$desc}</p>
                 </li>";
        
        return $html;
    }

}
