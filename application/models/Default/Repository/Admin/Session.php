<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Session
 *
 * @author Renato David
 */
class Application_Model_Default_Repository_Admin_Session  extends Sky_Db_Repository_Abstract {
    //put your code here
    
    public function getById($id,$hash=null) {
        $func = $this->getTable('Session');
        
        $where = (!is_null($hash))?"{$hash}(session_id)=(?)":"session_id=(?)";
        $row = $func->fetchRow(array("{$where}"=>$id));
        
        if(count($row) > 0 ) return $row;
        
        return null;
    }
    
    public function getUserById($id,$hash=null) {
        $func = $this->getTable('Session');
        
        $where = (!is_null($hash))?"{$hash}(session_id)=(?)":"session_id=(?)";
        $row = $func->fetchRow(array("{$where}"=>$id));
        
        $user = $row->findDependentRowset($this->getTable('User'),'Session');
        if(count($user) > 0 ) return $user;
        
        return null;
    }
    
    public function getLikeUser() {
        $session = $this->getTable('Session');
        $select = $session->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                ->setIntegrityCheck(false)
                ->joinLeft(array('u' => $this->getTable('User')), "u.session_id = {$session}.session_id", array('user_id','role_id','username','uModified'=>'u.modified'))
                ->joinLeft(array('i' => $this->getTable('UserInfo')), "i.user_info_id = u.user_info_id", array('name', 'email'))
                ->joinLeft(array('r' => $this->getTable('role')), "r.role_id = u.role_id", array('description'))
                ->where("{$session}.modified + {$session}.lifetime > (?)",  time())
                ->order("{$session}.modified DESC");
                

        return $session->fetchAll($select);
    }
    
    public function getSessions(){
        return $this->getTable('Session')->fetchAll();
    }


    public function delete($id){
        return $this->getTable('Session')->delete(array('session_id=(?)'=>$id));
    }
    
    public function destroy($time){
	$this->getTable('Session')->delete(array('modified + lifetime < ?' => $time));
        return true;
    }
    
    public function update($data){
        $data['modified'] = time();
        return $this->getTable('Session')->update($data, array('session_id=?'=>$data['session_id']));
    }
    
    public function insert($data){
        $data['modified'] = time();
        return $this->getTable('Session')->insert($data);
    }

}

