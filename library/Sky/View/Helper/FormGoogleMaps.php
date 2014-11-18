<?php

/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: FormSelect.php 24158 2011-06-27 15:31:54Z ezimuel $
 */
/**
 * Abstract class for extension
 */
require_once 'Zend/View/Helper/HtmlElement.php';

/**
 * Helper to generate "select" list of options
 *
 * @category   Zend
 * @package    Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Sky_View_Helper_FormGoogleMaps extends Zend_View_Helper_FormElement {
    /**
     * Generates 'select' list of options.
     *
     * @access public
     *
     * @param string|array $name If a string, the element name.  If an
     * array, all other parameters are ignored, and the array elements
     * are extracted in place of added parameters.
     *
     * @param mixed $value The option value to mark as 'selected'; if an
     * array, will mark all values in the array as 'selected' (used for
     * multiple-select elements).
     *
     * @param array|string $attribs Attributes added to the 'select' tag.
     *
     * @param array $options An array of key-value pairs where the array
     * key is the radio value, and the array value is the radio text.
     *
     * @param string $listsep When disabled, use this list separator string
     * between list values.
     *
     * @return string The select tag and options XHTML.
     */

    const GOOGLE_MAPS_URL = "https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=";
    const CEP_URL = "http://republicavirtual.com.br/web_cep.php";

    public function formGoogleMaps($name, $value = null, $attribs = null) {

        if (!key_exists('zoom', $attribs)) {
            $attribs['zoom'] = 23;
        }
        
        if (!key_exists('default_zoom', $attribs)) {
            $attribs['default_zoom'] = 6;
        }

        if (!key_exists('sensor', $attribs)) {
            $attribs['sensor'] = 'false';
        }

        if (!key_exists('maps_url', $attribs)) {
            $attribs['maps_url'] = self::GOOGLE_MAPS_URL;
        }

        if (!key_exists('cep_url', $attribs)) {
            $attribs['cep_url'] = self::CEP_URL;
        }

        if (!key_exists('title', $attribs)) {
            $attribs['title'] = 'Google Maps';
        }
        
        if (!key_exists('latlng', $attribs)) {
            $attribs['latlng'] = '-22.546052,-48.635514';
        } else {
            $attribs['default_zoom'] = $attribs['zoom'];
        }

        if (!key_exists('fil', $attribs)) {
            $attribs['fil'] = '';
        }



        $info = $this->_getInfo($name, $value, $attribs);
        extract($info); // name, value, attribs, options, listsep, disable
        // build the element
        $disabled = '';
        if ($disable) {
            // disabled
            $disabled = ' disabled="disabled"';
        }

        // XHTML or HTML end tag?
        $endTag = ' />';
        if (($this->view instanceof Zend_View_Abstract) && !$this->view->doctype()->isXhtml()) {
            $endTag = '>';
        }

        $xhtml = '<input type="hidden"'
                . ' id="' . $this->view->escape($id) . '_coord"'
                . ' name="' . $this->view->escape($id) . '_coord"'
                . ' value="' . $this->view->escape($attribs['latlng']) . '"'
                . $endTag;

        $xhtml .= '<input type="text"'
                . ' name="' . $this->view->escape($name) . '"'
                . ' id="' . $this->view->escape($id) . '"'
                . ' value="' . $this->view->escape($value) . '"'
                . $disabled
                . $this->_htmlAttribs($attribs)
                . $endTag;

        $this->view->headScript()->appendFile($attribs['maps_url'] . $attribs['sensor'], 'text/javascript');

        
        
        
        $script = " var {$this->view->escape($id)}_geocoder;
                    var {$this->view->escape($id)}_map;
                    var {$this->view->escape($id)}_marker;

                    function {$this->view->escape($id)}_initialize() {
        
        
                            var latlng = new google.maps.LatLng({$attribs['latlng']});
        
        
                                options = {  
                                center: latlng,
                                streetViewControl: true,
                                zoom: {$attribs['default_zoom']},
                                mapTypeId: google.maps.MapTypeId.ROADMAP,
                                mapTypeControlOptions: {
                                    style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
                                    position: google.maps.ControlPosition.LEFT_BOTTOM
                                }
                            }; 

                            {$this->view->escape($id)}_map = new google.maps.Map(document.getElementById('{$this->view->escape($id)}_map'), options);

                            {$this->view->escape($id)}_geocoder = new google.maps.Geocoder();

                            {$this->view->escape($id)}_marker = new google.maps.Marker({
                                    map: {$this->view->escape($id)}_map,
                                    draggable: true,
                                    title: '{$attribs['title']}',
                                    clickable: true,
                                    draggable: true
                            });

                            {$this->view->escape($id)}_marker.setPosition(latlng);
                            
                    }      

                       
                $(function(){   
                                    
                    {$this->view->escape($id)}_initialize()
                  
                    function {$this->view->escape($id)}_carregarNoMapa(endereco) {
                            {$this->view->escape($id)}_geocoder.geocode({ 'address': endereco + ', Brasil', 'region': 'BR' }, function (results, status) {
                                    if (status == google.maps.GeocoderStatus.OK) {
                                        

                                        var latitude = results[0].geometry.location.lat();
					var longitude = results[0].geometry.location.lng();        
                                        var location = new google.maps.LatLng(latitude, longitude);
					{$this->view->escape($id)}_marker.setPosition(location);
					{$this->view->escape($id)}_map.setCenter(location);
					{$this->view->escape($id)}_map.setZoom({$attribs['zoom']});
                                        
                                        $('#{$this->view->escape($id)}_coord').val(latitude+','+longitude);
                                    }
                            })
                    }   
                       
                    
                    google.maps.event.addListener({$this->view->escape($id)}_marker, 'drag', function () {
                            {$this->view->escape($id)}_geocoder.geocode({ 'latLng': {$this->view->escape($id)}_marker.getPosition() }, function (results, status) {
                                    if (status == google.maps.GeocoderStatus.OK) {
                                        
                                        var latitude = results[0].geometry.location.lat();
					var longitude = results[0].geometry.location.lng(); 
                                        
                                        $('#{$this->view->escape($id)}_coord').val(latitude+','+longitude);
                                    }
                            });
                    });                            
                                                
                    jQuery(\"#{$this->view->escape($id)}\").change(function(e){
                        if(jQuery.trim(jQuery(\"#{$this->view->escape($id)}\").val()) != \"\"){
                            jQuery.getScript(\"" . $attribs['cep_url'] . "?formato=javascript&cep=\"+jQuery(\"#{$this->view->escape($id)}\").val(), function(){
                                if(resultadoCEP[\"resultado\"]){
                                    jQuery(\"{$attribs['fil']}.endereco\").val(unescape(resultadoCEP[\"tipo_logradouro\"])+\" \"+unescape(resultadoCEP[\"logradouro\"]));
                                    jQuery(\"{$attribs['fil']}.bairro\").val(unescape(resultadoCEP[\"bairro\"]));
                                    jQuery(\"{$attribs['fil']}.cidade\").val(unescape(resultadoCEP[\"cidade\"]));
                                    jQuery(\"{$attribs['fil']}.estado\").val(unescape(resultadoCEP[\"uf\"]));
                                    jQuery(\"{$attribs['fil']}.cep\").val(jQuery(\"#{$this->view->escape($id)}\").val());
                                    var address = unescape(resultadoCEP[\"logradouro\"]) + \", \" + unescape(resultadoCEP[\"bairro\"]) + \", \" + unescape(resultadoCEP[\"cidade\"]);

                                    {$this->view->escape($id)}_carregarNoMapa(address);
                                }
                            });				
                        }		
                    })  
               })";


        $this->view->inlineScript()->appendScript($script);


        return $xhtml;
    }

}