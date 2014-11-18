<?php

/**
 * Helper to show HTML
 *
 */
class Zend_View_Helper_FormHtml extends Zend_View_Helper_FormElement
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
    public function formHtml($name, $value = null, $attribs = null)
    {
        $info = $this->_getInfo($name, $value, $attribs);
        extract($info); // name, value, attribs, options, listsep, disable

         // Render the button.
        $xhtml = '<div id="' . $this->view->escape($id) . '" ' . $this->_htmlAttribs($attribs). '>' . $value . '</div>';
       
        return $xhtml;
    }
}