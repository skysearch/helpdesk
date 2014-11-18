<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
interface Application_Model_Interface_Session {
    
    public function getById($id);
    public function getUserById($id);
    public function getSessions();
    public function delete($id);
    public function destroy($time);
    public function update($data);
    public function insert($data);  
}

