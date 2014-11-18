<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Sky_Db_Repository_Helper_FormatDataType {

    static public function set($col, $value, $dataType = null) {

        $data = array($col => $value);

        if (!is_array($dataType))
            return $data[$col];

        if (key_exists($col, $dataType)) {

            switch ($dataType[$col]['type']) {
                case 'datetime':
                    if (empty($data[$col])) {
                        $data[$col] = (key_exists('default', $data[$col])) ? $data[$col]['default'] : '0000-00-00 00:00:00';
                        break;
                    }
                    $date = str_replace(array('/', '.'), '-', $data[$col]);
                    $date = new DateTime($date);
                    $data[$col] = $date->format('Y-m-d H:i:s');
                    break;

                case 'now':
                    $date = new DateTime();
                    $data[$col] = $date->format('Y-m-d H:i:s');

                case 'crypt':
                    $data[$col] = Sky_Text_Transform_Crypt::encrypt($data[$col], true);
                    break;

                case 'slug':
                    $data[$col] = Sky_Text_Transform_Slug::makeSlugs($data[$col]);
                    break;

                case 'int':
                case 'float':
                case 'string':
                case 'bool':
                    $function = $dataType[$col]['type'];
                    $value = $data[$col];
                    eval("\$data[\"$col\"] = ($function)\"$value\";");
                    break;

                case 'currency':
                    $data[$col] = str_replace(',', '.', str_replace('.', '', $data[$col]));
                    break;

                case 'switch':
                    if ($dataType[$col]['cases']['type'] == 'bool') {
                        $args = $dataType[$col]['cases']['args'];
                        return ((bool) $data[$col]) ? $args[0] : $args[1];
                    }

                    if (!key_exists($data[$col], $dataType[$col]['cases']))
                        return null;

                    $data[$col] = $args[$data[$col]];
                    break;
                
                
                default:
                    $function = $dataType[$col]['type'];
                    $value = $data[$col];
                    eval("\$data[\"$col\"] = $function(\"$value\");");
                    break;
            }
        }
        return $data[$col];
    }

    static public function get($col, $data, $dataType = null) {

        if (!is_array($dataType))
            return $data[$col];

        if (key_exists($col, $dataType)) {

            switch ($dataType[$col]['type']) {
                case 'datetime':
                    if ((int) $data[$col] > 0) {
                        $data[$col] = new DateTime($data[$col]);
                        break;
                    }
                    $data[$col] = null;
                    break;

                case 'now':
                    $data[$col] = new DateTime();
                    break;

                case 'crypt':
                    $data[$col] = Sky_Text_Transform_Crypt::decrypt($data[$col], true);
                    break;

                case 'slug':
                    $data[$col] = $data[$col];
                    break;

                case 'int':
                case 'float':
                case 'string':
                case 'bool':
                    $function = $dataType[$col]['type'];
                    $value = $data[$col];
                    eval("\$data[\"$col\"] = ($function)\"$value\";");
                    break;

                case 'currency':
                    $data[$col] = number_format($data[$col], 2, ',', '.');
                    break;

                case 'switch':
                    if ($dataType[$col]['cases']['type'] == 'bool') {
                        $args = $dataType[$col]['cases']['args'];
                        return ((bool) $data[$col]) ? $args[0] : $args[1];
                    }

                    if (!key_exists($data[$col], $dataType[$col]['cases']))
                        return null;

                    $data[$col] = $args[$data[$col]];
                    break;

                default:
                    $function = $dataType[$col]['type'];
                    $value = $data[$col];
                    eval("\$data[\"$col\"] = $function(\"$value\");");
                    break;
            }
        }
        
        return $data[$col];
    }

}

?>
