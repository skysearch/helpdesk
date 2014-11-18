<?php

/**
 * Helper to show HTML
 *
 */
class Zend_View_Helper_FormSelectText extends Zend_View_Helper_FormElement
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
    public function formSelectText($name, $value = null, $attribs = null)
    {
        $info = $this->_getInfo($name, $value, $attribs);
        extract($info); // name, value, attribs, options, listsep, disable

        $inputs = '';
        $xhtml = '';

        $inputs = '';
        if(is_array($info['attribs']['values'])){
            $inputs = '<select id="' . $this->view->escape($id) . '" ' . $this->_htmlAttribs($attribs). '>'."\n";
            foreach($info['attribs']['values'] as $key=>$val){
                $inputs .= "<option value=\"{$key}\">{$val}</option>\n";
            }
            $inputs .='</select>'."\n";
        }
        
        
        if(!empty($info['attribs']['text'])){
            $xhtml = str_replace('%s',$inputs,$info['attribs']['text']);
        }
        
        
        return $xhtml;
    }
}