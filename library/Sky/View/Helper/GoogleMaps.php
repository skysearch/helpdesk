<?php

class Sky_View_Helper_GoogleMaps extends Zend_View_Helper_Abstract {


    const GOOGLE_MAPS_URL = "https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=";
    const CEP_URL = "http://republicavirtual.com.br/web_cep.php";

    public function googleMaps($id, array $field, $rowns = null, $attribs = array()) {

        if (!key_exists('zoom', $attribs)) {
            $attribs['zoom'] = 10;
        }
        
        if (!key_exists('default_zoom', $attribs)) {
            $attribs['default_zoom'] = 8;
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

        if($rowns instanceof Sky_Db_Table_Row) {
            $rowns = $rowns->toArray();
        }

        $marker = "var locations = [";
        $i = 0;
        foreach ($rowns as $mk) {
            $coord = explode(',',$mk[$field['latlng']]);
            $marker .= "\n['{$this->view->escape($mk[$field['title']])}', {$coord[0]}, {$coord[1]}, {$attribs['zoom']}],";
             $i++;
        }
        $marker = substr($marker, 0,-1);
        $marker .= "]";
        
        $this->view->headScript()->appendFile($attribs['maps_url'] . $attribs['sensor'], 'text/javascript');
        
        $script = " var geocoder;
                    var map;
                    var marker;

                    function initialize() {
        
        
                            var latlng = new google.maps.LatLng({$attribs['latlng']});
        
      
                                options = {  
                                center: latlng,
                                streetViewControl: true,
                                zoom: {$attribs['default_zoom']},
                                mapTypeId: google.maps.MapTypeId.ROADMAP
                            }; 

                            map = new google.maps.Map(document.getElementById('{$this->view->escape($id)}'), options);

                           
                            {$marker}
                            


                            var infowindow = new google.maps.InfoWindow();

                            var marker, i;

                            for (i = 0; i < locations.length; i++) {  
                                marker = new google.maps.Marker({
                                    position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                                    map: map,
                                    zoom: locations[i][3],
                                    title: locations[i][0]
                                });

                                google.maps.event.addListener(marker, 'click', (function(marker, i) {
                                    return function() {
                                    infowindow.setContent(locations[i][0]);
                                    infowindow.open(map, marker);
                                    }
                                })(marker, i));
                            }
                            
                    }    
                            
                            
                    jQuery(function(){
                            initialize();
                    })        
                            
                ";


        $this->view->inlineScript()->appendScript($script);

        $attr = '';
        foreach ($attribs as $key => $value) {
            $attr .="{$key}=\"{$value}\" ";
        }
        
        $xhtml = "<div id=\"{$this->view->escape($id)}\" {$attr} ></div>";

        return $xhtml;
    }

}