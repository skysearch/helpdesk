<?php

/**
 * Helper to show HTML
 *
 */
class Zend_View_Helper_FormLink extends Zend_View_Helper_FormElement
{
    /**
     * Helper to show a html in a form
     *
     * @param string|array $name If a string, the element name.  If an
     * array, all other parameters are ignored, and the array elements
     * are extracted in place of added parameters.
     *
     * @param mixed $value The element value.
     *
     * @param array $attribs Attributes for the element tag.
     *
     * @return string The element XHTML.
     */
    public function formLink($name, $value = null, $attribs = null)
    {
        $info = $this->_getInfo($name, $value, $attribs);
        extract($info); // name, value, attribs, options, listsep, disable

        $label = (key_exists('text', $attribs))?$attribs['text']:$this->view->escape($value);
        
         // Render the button.
        $xhtml = '<div id="' . $this->view->escape($id) . '" ' . $this->_htmlAttribs($attribs). '>' . 
                 '<a href="' . $this->view->baseUrl($this->view->escape($value)) . '">' . $this->view->escape($label) . '</a></div>';
       
        return $xhtml;
    }
}