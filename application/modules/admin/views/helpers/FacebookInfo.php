<?php

class Admin_View_Helper_FacebookInfo extends Zend_View_Helper_Abstract 
{
    public function facebookInfo($type='normal'){
    	$html = "<div id=\"user-info\"></div>
					<div id=\"debug\"></div>
					<div id=\"other\" style=\"display:none\"></div>";
        return $html;
            
    }
}
