<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
interface Application_Model_Interface_User {
    
    public function getSessionById($id);
    public function deleteSession($id);
    public function deleteUser($id);
    public function authenticate($username, $password, $type);
    public function toggleStatus($id);
    public function updatePasswordFor($username, $password);
    public function insert($data);
    public function update($data, $id);
    public function updateInfo($data, $id);
    public function insertInfo($data);
    public function getById($id);
    public function getInfoByUserId($id);
    public function getAll($where = null, $order = null, $count = null);
    public function count();
    public function exist($checkFor, $value, $pass = null);
    public function getToDataTable($post, $columns);
}

