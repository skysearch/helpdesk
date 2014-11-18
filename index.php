<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * {
            	'bSort'=> false,
                'bProcessing'=> true,
                'bServerSide'=> true,
                'bStateSave'=> true,
                'sAjaxSource'=> '{$url}',
                'sServerMethod'=> 'POST',
                'oLanguage'=> {
                    'sUrl'=> '{$this->view->skin}/js/datatables/media/languages/pt_BR.txt'
                }
            }
 */



exit;
date_default_timezone_set('America/Sao_Paulo');

if (version_compare(phpversion(), '5.3.0', '<') === true) {
    die('ERROR: Your PHP version is ' . phpversion() . '. Requires PHP 5.3.0 or newer.');
}


defined('DS') || define('DS', DIRECTORY_SEPARATOR);
defined('PS') || define('PS', PATH_SEPARATOR);

defined('PUBLIC_PATH') || define('PUBLIC_PATH', realpath(dirname(__FILE__)));
defined('ROOT_PATH') || define('ROOT_PATH', realpath(PUBLIC_PATH . DS . '..'));
defined('TEMP_PATH') || define('TEMP_PATH', ROOT_PATH . DS . 'temp');
defined('LIBRARY_PATH') || define('LIBRARY_PATH', ROOT_PATH . DS . 'library');
defined('APPLICATION_PATH') || define('APPLICATION_PATH', ROOT_PATH . DS . 'application');
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));




// Ensure library/ is on include_path
set_include_path(implode(PS, array(
    realpath(LIBRARY_PATH),
    get_include_path(),
)));


/** Zend_Application */
require_once 'Zend' . DS . 'Loader.php';

Zend_Loader::registerAutoload();



$array = array(
    'bSort'=> false,
    'bProcessing' => true,
    'bServerSide'=> true,
    'bStateSave'=> true,
    'sAjaxSource'=> '{$url}',
    'sServerMethod'=> 'POST',
    'oLanguage'=> array(
        'sProcessing'=>   'Processando...',
        'sLengthMenu'=>   'Mostrar _MENU_ registros',
        'sZeroRecords'=>  'Não foram encontrados resultados',
        'sInfo'=>         'Mostrando de _START_ até _END_ de _TOTAL_ registros',
        'sInfoEmpty'=>    'Mostrando de 0 até 0 de 0 registros',
        'sInfoFiltered'=> '(filtrado de _MAX_ registros no total)',
        'sInfoPostFix'=>  '',
        'sSearch'=>       'Buscar:',
        'sUrl'=>          '',
        'oPaginate'=> array(
            'sFirst'=>    'Primeiro',
            'sPrevious'=> 'Anterior',
            'sNext'=>     'Seguinte',
            'sLast'=>     'Último'
        )
    )
    
);


print(Zend_Json::encode($array));

/*
{
    "bSort":false,
    "bProcessing":true,
    "bServerSide":true,
    "bStateSave":true,
    "sAjaxSource":"{$url}",
    "sServerMethod":"POST",
    "oLanguage":{
        "sProcessing":"Processando...",
        "sLengthMenu":"Mostrar _MENU_ registros",
        "sZeroRecords":"N\u00e3o foram encontrados resultados",
        "sInfo":"Mostrando de _START_ at\u00e9 _END_ de _TOTAL_ registros",
        "sInfoEmpty":"Mostrando de 0 at\u00e9 0 de 0 registros",
        "sInfoFiltered":"(filtrado de _MAX_ registros no total)",
        "sInfoPostFix":"",
        "sSearch":"Buscar:",
        "sUrl":"",
        "oPaginate":{
            "sFirst":"Primeiro",
            "sPrevious":"Anterior",
            "sNext":"Seguinte",
            "sLast":"\u00daltimo"
         }
     }
}
 * */
 
?>