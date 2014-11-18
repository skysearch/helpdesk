<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Application_Model_Default_Repository_Api_User extends Sky_Db_Repository_Abstract  {


    protected $_session;
    private $_data;

    public function deleteUser($id) {
    	$user = $this->getById($id);
    	return (bool) $user->delete();
    }
    
    public function authenticate($username, $password, $type = 'username') {

        if ($this->exist($type, $username, $password)) {
            $row = $this->getById($this->_data['user_id']);
            $row->session_id = Zend_Session::getId();
            $user = $row->toArray();

            $info = $row->findParentRow($this->getTable('UserInfo'));
            if (count($info) > 0)
                $info = $info->toArray();

            $role = $row->findParentRow($this->getTable('Role'), 'Role');
            if (count($role) > 0)
                $role = $role->toArray();

            unset($user['password']);

            $row->save();

            return array('user' => $user, 'info' => $info, 'role' => $role);
        }

        return null;
    }

    public function toggleStatus($id) {
        $user = $this->getTable('User');
        $select = $user->select()
                ->from($user, array('user_id', 'status'))
                ->where('user_id=(?)', $id);

        $row = $user->fetchRow($select);

        if (count($row) > 0) {
            $row->status = 1 - (int) $row['status'];
            $row->save();

            return true;
        }

        return false;
    }

    public function updatePasswordFor($username, $password) {
        $user = $this->getTable('User');
        $select = $user->select()->where('username=(?)', $username);
        $row = $user->fetchRow($select);

        if (count($row) > 0) {
            $row->password = $password;

            if ($row->save())
                return true;
        }

        return false;
    }

    public function insert($data) {
        return (int) $this->getTable('User')->insert($data);
    }

    public function update($data, $id) {
        return (bool) $this->getTable('User')->update($data, array('user_id=(?)' => $id));
    }

    public function getById($id) {
        return $this->getTable('User')->find($id)->current();
    }

    public function getAll($where = null, $order = null, $count = null) {
        return $this->getTable('User')->fetchAll($where, $order, $count);
    }

    public function count() {
        $user = $this->getTable('User');
        $select = $user->select()->from($user, array('COUNT(*) as total'));
        $row = $user->fetchRow($select);
        return (int) $row['total'];
    }

    public function exist($checkFor, $value, $pass = null) {
        $user = $this->getTable('User');
        $select = $user->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                ->setIntegrityCheck(false)
                ->from(array('u' => $user), array('user_id', 'username', 'role_id', 'created', 'modified'));

        if (!is_null($pass)) {
            $select->where('u.password=SHA1(?)', $pass);
        }

        switch ($checkFor) {
            case 'username':
                $select->where('u.username=(?)', $value);
                break;

            case 'email':
                $select->join(array('i' => $this->getTable('UserInfo')), 'u.user_info_id=i.user_info_id', array('i.email'))
                        ->where('i.email=(?)', $value);
                break;
        }



        $select->limit(1);
        $this->_data = $user->fetchRow($select);
        return (count($this->_data) > 0) ? true : false;
    }

    public function getToDataTable($post, $columns) {
        $user = $this->getTable('User');
        $helper = $this->getHelper('DataTable');

        $select = $user->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                ->setIntegrityCheck(false)
                ->joinLeft(array('r' => $this->getTable('role')), "r.role_id = {$user}.role_id", array('description'));


        $helper->setColumns($columns);

        return $helper->getJsonDataTable($user, $select, $post,'user_id',array('del','edit'));
    }

}

