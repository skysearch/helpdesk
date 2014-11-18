<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$configs = Sky_Module_Config::getTable('api');

if ($_GET['token'] == md5($configs['token'])) {
    $backup = new Sky_Backup();
    $backup->setType('Table');
    $backup->save();
}

