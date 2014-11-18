<?php

/*
 * 
 * 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * 
  $options = array('actions' => array(
  'editar' => array('label' => 'Editar',
  'type' => 'direct',
  'url' => array('action' => 'editar'),
  'params' => array()),
  'apagar' => array('label' => 'Apagar',
  'type' => 'dialog',
  'dialog' => array('question' => 'Apagar registro?',
  'description' => 'Esse item será apagado. Você tem certeza que deseja continuar?',
  'buttons' => array(
  0 => array('text' => 'Cancelar'),
  1 => array(
  'text' => 'Apagar registro',
  'url' => array('action' => 'apagar')))),
  'params' => array()),
  'exibir' => array('label' => 'Exibri',
  'type' => 'direct',
  'url' => array('action' => 'exibir'),
  'params' => array()),
  ));
 * 
 */

class Sky_View_Helper_DataTables extends Zend_View_Helper_Abstract {

    protected $_scripts;
    protected $_html;

    public function dataTables($element, $columns, $rows = null, $type = 'json', $configs = null, $merge = true) {


        $options = array('actions' => array(
                'editar' => array('label' => 'Editar',
                    'type' => 'direct',
                    'url' => array('action' => 'editar'),
                    'params' => array()),
                'apagar' => array('label' => 'Apagar',
                    'type' => 'dialog',
                    'dialog' => array('question' => 'Apagar registro?',
                        'description' => 'Esse item será apagado. Você tem certeza que deseja continuar?',
                        'buttons' => array(
                            0 => array('text' => 'Cancelar'),
                            1 => array(
                                'text' => 'Apagar registro',
                                'url' => array('action' => 'apagar')))),
                    'params' => array()),
        ));

        if ($merge) {
            if (!is_null($configs)) {
                $options = array_merge_recursive($options, $configs);
            }
        } else {
            $options = $configs;
        }



        $name = str_replace('#', '', $element);
        $scripts = "";

        $data_action = array();
        foreach ($options['actions'] as $k => $v) {
            $data_action[$k] = $v['label'];
        }

        $data_action = "{name:'actions','value':'" . serialize($data_action) . "'}";



        $this->_html = $this->_creatTable($name, $columns, $rows, null, $options['actions']);

        if ($type == 'json') {
            $datatable = array('datatable' => array(
                    'bSort' => false,
                    'bProcessing' => true,
                    'bServerSide' => true,
                    'bStateSave' => true,
                    'sAjaxSource' => $this->view->url(array('action' => 'listar', 'format' => 'json')),
                    'sServerMethod' => 'POST',
                    'oLanguage' => array(
                        'sProcessing' => 'Processando...',
                        'sLengthMenu' => 'Mostrar _MENU_ registros',
                        'sZeroRecords' => 'Não foram encontrados resultados',
                        'sInfo' => 'Mostrando de _START_ até _END_ de _TOTAL_ registros',
                        'sInfoEmpty' => 'Mostrando de 0 até 0 de 0 registros',
                        'sInfoFiltered' => '(filtrado de _MAX_ registros no total)',
                        'sInfoPostFix' => '',
                        'sSearch' => 'Buscar:',
                        'sUrl' => '',
                        'oPaginate' => array(
                            'sFirst' => 'Primeiro',
                            'sPrevious' => 'Anterior',
                            'sNext' => 'Seguinte',
                            'sLast' => 'Último'
                        )
                    )
            ));

            $options = array_merge_recursive($options, $datatable);


            $scripts = "$.extend($.fn.dataTable.defaults, {
                        \"fnServerParams\": function ( aoData ) {
                            aoData.push({$data_action},{name:'element',value:'{$name}'});
                        }
                    });";

            $scripts .= "var {$name}Table = $('{$element}').dataTable(" . $this->_prepateParam($options['datatable']) . ");";
        }

        $scripts_actions = $this->_getActions($element, $options);
        $scripts = "<script type=\"text/javascript\">$(function(){ var id_reg; {$scripts} {$scripts_actions} })</script>";

        return $scripts . $this->_html;
    }

    protected function _getActions($element, $options = null) {
        $funcions = "";
        foreach ($options['actions'] as $funcion => $option) {
            $funcions .= $this->_createFunctions($element, $funcion, $option);
        }

        return $funcions;
    }

    protected function _createFunctions($id, $function, $options) {
        $name = str_replace('#', '', $id);
        $scripts = "";

        $default = array('dialog' => array(
                'autoOpen' => false,
                'resizable' => false,
                'height' => 150,
                'width' => 350,
                'modal' => true
        ));

        $params = array_merge_recursive($default, $options);



        if ($params['type'] == 'direct') {
            $scripts .= "
                 $.{$name}_{$function} = function(id){
                    id_reg = id;
                    document.location = '{$this->view->url($params['url'])}/id/'+id_reg;
                    return false;
                 }";
        } else if ($params['type'] == 'dialog') {
            $buttons = $params['dialog']['buttons'];

            $bt_js = "";
            foreach ($buttons as $bt) {
                $url = (key_exists('url', $bt)) ? "document.location ='{$this->view->url($bt['url'])}/id/'+id_reg;" : "";
                $bt_js .="'{$bt['text']}':function(){{$url} $(this).dialog('close');},";
            }

            $params['dialog']['buttons'] = "{" . substr($bt_js, 0, -1) . "}";


            $scripts .= "
                $.{$name}_{$function} = function(id){
                    id_reg = id;
                    $('{$id}-{$function}-confirm').dialog('open');
                    return false;
                } 
                $('{$id}-{$function}-confirm').dialog(" . $this->_prepateParam($params['dialog']) . ");";

            $this->_html .= '<div id="' . $name . '-' . $function . '-confirm" title="' . $params['dialog']['question'] . '">
                                <p>
                                    <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>' .
                    $params['dialog']['description'] . '
                                </p>
                            </div>';
        }

        return $scripts;
    }

    protected function _prepateParam($params) {
        $param = "";
        $i = 1;
        foreach ($params as $key => $value) {

            if (is_bool($value)) {
                $value = ($value) ? "true" : "false";
            } else {
                if (is_array($value)) {
                    $param .= "'{$key}':" . self::_prepateParam($value);
                    continue;
                }
                if (stripos($value, '()') === false) {
                    $value = "'{$value}'";
                }
            }

            $param .= "'$key':" . $value;

            if ($i < count($params)) {
                $param .= ",";
            }

            $i++;
        }
        return "{" . $param . "}";
    }

    protected function _creatTable($id, $fhead, $tbody = null, $tfoot = null, $actions = null) {

        $count = count($fhead);
        $header = "";

        foreach ($fhead as $value) {
            $header .= "<th width=\"{$value['width']}\">{$value['label']}</th>";
        }
        if (!is_null($actions)) {
            $count = $count + count($actions);
            foreach ($actions as $k => $v) {
                $header .= "<th class=\"{$k}\" width=\"5%\">&nbsp;</th>\n";
            }
        }

        $header = "<tr>{$header}</tr>";

        $body = "<tr><td colspan=\"{$count}\" class=\"dataTables_empty\">Carregando dados do servidor</td></tr>";
        if (!is_null($tbody)) {
            $body = "";
            foreach ($tbody as $rows) {
                $td = "";
                foreach ($rows as $k => $v) {
                    if (!key_exists($k, $fhead))
                        continue;

                    $v = ($v instanceof DateTime) ? $v->format($columns[$y]['format']) : $v;
                    $td .= "<td class=\"{$k}\">{$v}</td>";
                }
                $body .= "<tr>{$td}</tr>";
            }
        }

        $html = '<table cellpadding="0" cellspacing="0" border="0" class="display" id="%s">
                    <thead>
                        %s
                    </thead>
                    <tbody>
                        %s
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="%d">%s</th>
                        </tr>
                    </tfoot>
                 </table>';

        return sprintf($html, $id, $header, $body, $count, $tfoot);
    }

}
