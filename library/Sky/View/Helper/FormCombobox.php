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
require_once 'Zend/View/Helper/FormElement.php';

/**
 * Helper to generate "select" list of options
 *
 * @category   Zend
 * @package    Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Sky_View_Helper_FormCombobox extends Zend_View_Helper_FormElement {

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
    public function formCombobox($name, $value = null, $attribs = null, $options = null, $listsep = "<br />\n") {

        if (!key_exists('delay', $attribs)) {
            $attribs['delay'] = 0;
        }

        if (!key_exists('minLength', $attribs)) {
            $attribs['minLength'] = 0;
        }

        if (!key_exists('sourceType', $attribs)) {
            $attribs['sourceType'] = 'select';
        }


        if ($attribs['sourceType'] == 'json') {
            if (!key_exists('source', $attribs)) {
                throw new Zend_Exception('Source json faltando.');
            }
            $source = "function(request, response) {
                if (!request.term.length) {
                    response(_self.options.initialValues);
                } else {
                    if (typeof _self.options.source === \"function\") {
                        _self.options.source(request, response);
                    } else if (typeof _self.options.source === \"string\") {
                        $.ajax({
                            url: {$attribs['source']},
                            data: request,
                            dataType: \"json\",
                            success: function(data, status) {
                                response(data);
                            },
                            error: function() {
                                response([]);
                            }
                        });
                    }
                }
            }";
        } else if ($attribs['sourceType'] == 'select') {
            $source = "function( request, response ) {
                                var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), \"i\" );
                                response( select.children( \"option\" ).map(function() {
                                    var text = $( this ).text();
                                    if ( this.value && ( !request.term || matcher.test(text) ) )
                                        return {
                                            label: text.replace(
                                                new RegExp(
                                                    \"(?![^&;]+;)(?!<[^<>]*)(\" +
                                                    $.ui.autocomplete.escapeRegex(request.term) +
                                                    \")(?![^<>]*>)(?![^&;]+;)\", \"gi\"
                                                ), \"<strong>$1</strong>\" ),
                                            value: text,
                                            option: this
                                        };
                                }) );
                            }";
        }





        $info = $this->_getInfo($name, $value, $attribs, $options, $listsep);
        extract($info); // name, id, value, attribs, options, listsep, disable
        // force $value to array so we can compare multiple values to multiple
        // options; also ensure it's a string for comparison purposes.
        $value = array_map('strval', (array) $value);

        // check if element may have multiple values
        $multiple = '';

        if (substr($name, -2) == '[]') {
            // multiple implied by the name
            $multiple = ' multiple="multiple"';
        }

        if (isset($attribs['multiple'])) {
            // Attribute set
            if ($attribs['multiple']) {
                // True attribute; set multiple attribute
                $multiple = ' multiple="multiple"';

                // Make sure name indicates multiple values are allowed
                if (!empty($multiple) && (substr($name, -2) != '[]')) {
                    $name .= '[]';
                }
            } else {
                // False attribute; ensure attribute not set
                $multiple = '';
            }
            unset($attribs['multiple']);
        }

        // now start building the XHTML.
        $disabled = '';
        if (true === $disable) {
            $disabled = ' disabled="disabled"';
        }

        // Build the surrounding select element first.
        $xhtml = '<select'
                . ' name="' . $this->view->escape($name) . '" '
                . ' id="' . $this->view->escape($id) . '"'
                . $multiple
                . $disabled
                . $this->_htmlAttribs($attribs)
                . ">\n    ";

        // build the list of options
        $list = array();
        $translator = $this->getTranslator();
        foreach ((array) $options as $opt_value => $opt_label) {
            if (is_array($opt_label)) {
                $opt_disable = '';
                if (is_array($disable) && in_array($opt_value, $disable)) {
                    $opt_disable = ' disabled="disabled"';
                }
                if (null !== $translator) {
                    $opt_value = $translator->translate($opt_value);
                }
                $opt_id = ' id="' . $this->view->escape($id) . '-optgroup-'
                        . $this->view->escape($opt_value) . '"';
                $list[] = '<optgroup'
                        . $opt_disable
                        . $opt_id
                        . ' label="' . $this->view->escape($opt_value) . '">';
                foreach ($opt_label as $val => $lab) {
                    $list[] = $this->_build($val, $lab, $value, $disable);
                }
                $list[] = '</optgroup>';
            } else {
                $list[] = $this->_build($opt_value, $opt_label, $value, $disable);
            }
        }

        // add the options to the xhtml and close the select
        $xhtml .= implode("\n    ", $list) . "\n</select>";



        $script = '(function( $ ) {
            $.widget( "ui.combobox", {
                _create: function() {
                    var input,
                        that = this,
                        select = this.element.hide(),
                        selected = select.children( ":selected" ),
                        value = selected.val() ? selected.text() : "",
                        wrapper = this.wrapper = $( "<span>" )
                            .addClass( "ui-combobox" )
                            .insertAfter( select );

                    function removeIfInvalid(element) {
                        var value = $( element ).val(),
                            matcher = new RegExp( "^" + $.ui.autocomplete.escapeRegex( value ) + "$", "i" ),
                            valid = false;
                        select.children( "option" ).each(function() {
                            if ( $( this ).text().match( matcher ) ) {
                                this.selected = valid = true;
                                return false;
                            }
                        });
                        if ( !valid ) {

                            $( element )
                                .val( "" )
                                .attr( "title", value + " O ítem selecionado não existe na listagem." )
                                .tooltip( "open" );
                            select.val( "" );
                            setTimeout(function() {
                                input.tooltip( "close" ).attr( "title", "" );
                            }, 2500 );
                            input.data( "ui-autocomplete" ).term = "";
                            return false;
                        }
                    }

                    input = $( "<input>" )
                        .appendTo( wrapper )
                        .val( value )
                        .attr( "title", "" )
                        .addClass( "ui-state-default ui-combobox-input" )
                        .autocomplete({
                            delay: ' . $attribs['delay'] . ',
                            minLength: ' . $attribs['minLength'] . ',
                            source: ' . $source . ',
                            select: function( event, ui ) {
                                ui.item.option.selected = true;
                                that._trigger( "selected", event, {
                                    item: ui.item.option
                                });
                            },
                            change: function( event, ui ) {
                                if ( !ui.item )
                                    return removeIfInvalid( this );
                            }
                        })
                        .addClass( "ui-widget ui-widget-content ui-corner-left" );

                    
                    
                    input.data( "ui-autocomplete" )._renderItem = function( ul, item ) {
                    return $( "<li>" )
                        .append( "<a>" + item.label + "</a>" )
                        .appendTo( ul );
                    };

                    $( "<a>" )
                        .attr( "tabIndex", -1 )
                        .attr( "title", "Exibir todos os itens" )
                        
                        .appendTo( wrapper )
                        .button({
                            icons: {
                                primary: "ui-icon-triangle-1-s"
                            },
                            text: false
                        })
                        .removeClass( "ui-corner-all" )
                        .addClass( "ui-corner-right ui-combobox-toggle" )
                        .click(function() {
                            // close if already visible
                            if ( input.autocomplete( "widget" ).is( ":visible" ) ) {
                                input.autocomplete( "close" );
                                removeIfInvalid( input );
                                return;
                            }

                            // work around a bug (likely same cause as #5265)
                            $( this ).blur();

                            // pass empty string as value to search for, displaying all results
                            input.autocomplete( "search", "" );
                            input.focus();
                        });

                        input
                            .tooltip({
                                position: {
                                    of: this.button
                                },
                                tooltipClass: "ui-state-highlight"
                            });
                },

                destroy: function() {
                    this.wrapper.remove();
                    this.element.show();
                    $.Widget.prototype.destroy.call( this );
                }
            });
        })( jQuery );

        $(function() {
            $( "#' . $this->view->escape($id) . '").combobox();
        });';

        $this->view->headScript()->appendScript($script);



        return $xhtml;
    }

    /**
     * Builds the actual <option> tag
     *
     * @param string $value Options Value
     * @param string $label Options Label
     * @param array  $selected The option value(s) to mark as 'selected'
     * @param array|bool $disable Whether the select is disabled, or individual options are
     * @return string Option Tag XHTML
     */
    protected function _build($value, $label, $selected, $disable) {
        if (is_bool($disable)) {
            $disable = array();
        }

        $opt = '<option'
                . ' value="' . $this->view->escape($value) . '"'
                . ' label="' . $this->view->escape($label) . '"';

        // selected?
        if (in_array((string) $value, $selected)) {
            $opt .= ' selected="selected"';
        }

        // disabled?
        if (in_array($value, $disable)) {
            $opt .= ' disabled="disabled"';
        }

        $opt .= '>' . $this->view->escape($label) . "</option>";

        return $opt;
    }

}