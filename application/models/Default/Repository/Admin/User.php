<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Application_Model_Default_Repository_Admin_User extends Sky_Db_Repository_Abstract {

    const TEMPLATE_MAIL = 'user_template_mail';

    protected $_session;
    private $_data;

    public function recovery($username, $email) {
        $func = $this->getTable('User');
        $row = $func->fetchRow(array('username=(?)' => $username));

        if (count($row) > 0) {
            $user = $this->getInfoByUserId($row['user_id']);


            if (count($user) > 0) {

                if ($email != $user['email']) {
                    return false;
                }

                $newpass = chr(rand(97, 122)) .
                        chr(rand(65, 90)) .
                        chr(rand(65, 90)) .
                        chr(rand(65, 90)) .
                        chr(rand(97, 122)) .
                        chr(rand(65, 90)) .
                        chr(rand(97, 122)) .
                        chr(rand(97, 122));


                $row->password = $newpass;

                if ($row->save()) {
                    return array('name' => $user['name'], 'email' => $user['email'], 'password' => $newpass);
                }
            }
        }

        return false;
    }

    public function getSessionById($id) {
        $row = $this->getTable('User')->fetchRow(array('user_id=(?)' => $id));
        $session = $row->findParentRow($this->getTable('Session'));
        if (count($session) > 0)
            return $session;

        return null;
    }

    public function deleteSession($id) {
        $session = $this->getSessionById($id);
        if (count($session) > 0) {
            return (bool) $session->delete();
        }

        return false;
    }

    public function deleteUser($id) {
        $user = $this->getInfoByUserId($id);

        if (count($user) > 0) {
            return (bool) $user->delete();
        }
    }

    public function authenticate($username, $password, $type = 'username') {

        if ($this->exist($type, $username, $password)) {
            $row = $this->getById($this->_data['user_id']);
            $row->session_id = Zend_Session::getId();
            $user = $row->toArray();

            $info = $row->findParentRow($this->getTable('UserInfo'));
            if (count($info) > 0) {
                $info = $info->toArray();

                $info = array('name' => $info['name'], 'email' => $info['email'], 'cliente' => $info['cliente'], 'created' => $info['created'], 'modified' => $info['modified']);
            }

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
        $id = $this->getTable('User')->insert($data);

        $configs = Sky_Module_Config::getTable('user');
        $template = $configs[self::TEMPLATE_MAIL];

        if ($id > 0) {
            $mail = new Sky_Mail();
            $mail->setTemplate($data, $template);
            $mail->send("Novo Usuário - {$data['name']}");
        }

        return (int) $id;
    }

    public function update($data, $id) {
        return (bool) $this->getTable('User')->update($data, array('user_id=(?)' => $id));
    }

    public function updateInfo($data, $id) {
        $info = $this->getTable('UserInfo');
        $user = $this->getById($id);



        if (count($user) > 0) {
            return (bool) $info->update($data, array('user_info_id=(?)' => $user['user_info_id']));
        }

        return false;
    }

    public function insertInfo($data) {
        return (int) $this->getTable('UserInfo')->insert($data);
    }

    public function getById($id) {
        /* $info = Zend_Auth::getInstance()->getIdentity();
          if($info['role']['name']!='administrador'){
          $func = $this->getTable('User');
          $select = $func->select()->where('user_id=(?)',$info['user']['user_id']);

          return $func->fetchRow($select);
          } */

        return $this->getTable('User')->find($id)->current();
    }

    public function getInfoByUserId($id) {
        $user = $this->getById($id);
        $info = $user->findParentRow($this->getTable('UserInfo'));

        return (count($info) > 0) ? $info : null;
    }

    public function getAll($role = null, $order = null) {
        $user = $this->getTable('User');

        $select = $user->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                ->setIntegrityCheck(false)
                ->joinLeft(array('i' => $this->getTable('UserInfo')), "i.user_info_id = {$user}.user_info_id", array('info_name' => 'i.name', 'email'))
                ->joinLeft(array('r' => $this->getTable('role')), "r.role_id = {$user}.role_id", array('description'))
                ->where("{$user}.status=1");

        if (!is_null($role))
            $select->where('r.name=(?)', $role);

        if (!is_null($order)) {
            if (is_array($order)) {
                foreach ($order as $or) {
                    $select->order($or);
                }
            } else {
                $select->order($order);
            }
        }


        return $user->fetchAll($select);
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

    public function getToDataTable($post, $columns, $json = true) {
        $user = $this->getTable('User');
        $helper = $this->getHelper('DataTable');

        $select = $user->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                ->setIntegrityCheck(false)
                ->joinLeft(array('i' => $this->getTable('UserInfo')), "i.user_info_id = {$user}.user_info_id", array('info_name' => 'i.name', 'email'))
                ->joinLeft(array('r' => $this->getTable('role')), "r.role_id = {$user}.role_id", array('description'));


        $helper->setColumns($columns);


        if ($json)
            return $helper->getJsonDataTable($user, $select, $post, 'user_id', array('del', 'edit'));
        else
            return $user->fetchAll($select->order('user_id DESC'));
    }

    public function cpfExists($cpf) {
        $user = $this->getTable('UserInfo');
        $select = $user->select()->where('cpf=(?)', $cpf);

        $row = $user->fetchRow($select);

        if (count($row) > 0)
            return true;

        return false;
    }

    /*
     * Verifica se o cadastro do usuário foi confirmado
     * 
     */

    public function isConfirmed($id) {
        $row = $this->getInfoByUserId($id);

        if (count($row) > 0)
            return (bool) $row['confirmed'];

        return false;
    }

    /*
     * Verifica se o usuário é associado
     * 
     */

    public function isAssociate($id) {
        $row = $this->getInfoByUserId($id);

        if (count($row) > 0 && $this->isConfirmed($id))
            return (bool) $row['associate'];

        return false;
    }

    public function search($key = null, $start = null, $end = null, $order = null) {
        $func = $this->getTable('User');

        $select = $func->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                ->setIntegrityCheck(false)
                ->joinLeft(array('i' => $this->getTable('UserInfo')), "i.user_info_id = {$func}.user_info_id", array('name' => 'i.name', 'iEmail' => 'i.email', 'iEmail_Type' => 'i.email_type', 'iPhone' => 'i.phone_1', 'iPhone_Type' => 'i.phone_type_1', 'associate', 'iConfirmed' => 'i.confirmed', 'iCity' => 'i.city', 'avatar'))
                ->joinLeft(array('r' => $this->getTable('Role')), "r.role_id = {$func}.role_id", array('role' => 'name'));



        if (!is_null($start)) {
            $start = explode('/', $start);
            $select->where("DATE({$func}.created) >= '{$start[2]}-{$start[1]}-{$start[0]}'");

            if (!is_null($end)) {
                $end = explode('/', $end);
                $select->where("DATE({$func}.created) <= '{$end[2]}-{$end[1]}-{$end[0]}'");
            }
        }

        if (!is_null($key))
            $select->where("(i.name LIKE '%$key%' OR 
                             i.email='$key' OR
                             i.email_2='$key' OR
                             i.cpf='$key' OR 
                             i.phone_1 LIKE '%$key%' OR 
                             i.phone_2 LIKE '%$key%' OR 
                             i.crea_number='$key')");


        if (!is_null($order))
            $select->order("{$func}.created $order");


        $rows = $func->fetchAll($select);

        if (count($rows) > 0) {
            return $rows;
        }

        return null;
    }
    
    public function userByAreaApi($ativo = true, $associado = true, $order='ASC'){
        $func = $this->getTable('UserInfo');
        $select = $func->select()
                ->from($func,array('academic_area','COUNT(*) as total'))                
                ->group("{$func}.academic_area")
                ->order("academic_area {$order}");

        if ($associado)
            $select->where("{$func}.associate = 1");

        if ($ativo)
            $select->where("{$func}.confirmed = 1");

            
        
        $rows = $func->fetchAll($select);

        if (count($rows) > 0) {
            $c = Sky_Model_Factory::getInstance()->setModule('admin')->getCategoria();
            
            $regs = array();
            foreach ($rows->toArray() as $r) {
                $regs[] = array('area'=>$c->translate($r['academic_area']),'slug'=>$r['academic_area'],'total'=>$r['total']);
            }
            return $regs;
        }

        return null;
    }

    public function searchApi($categoria, $key = null, $ativo = true, $associado = true, $order = null, $limit = null) {
        $func = $this->getTable('User');
        
        $c = Sky_Model_Factory::getInstance()->setModule('admin')->getCategoria();
        
        $select = $func->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                ->setIntegrityCheck(false)
                ->joinLeft(array('i' => $this->getTable('UserInfo')), "i.user_info_id = {$func}.user_info_id", array('academic_area',
                    'academic_level',
                    'academic_field',
                    'panelist',
                    'phone_type_1',
                    'phone_1',
                    'phone_type_2',
                    'phone_2',
                    'email_type',
                    'email',
                    'email_type_2',
                    'email_2',
                    'address_type',
                    'address',
                    'number',
                    'complement',
                    'district',
                    'city',
                    'state',
                    'postal_code',
                    'address_type_2',
                    'address_2',
                    'number_2',
                    'complement_2',
                    'district_2',
                    'city_2',
                    'state_2',
                    'postal_code_2',
                    'info_id' => 'i.user_info_id',
                    'name' => 'i.name',
                    'iEmail' => 'i.email',
                    'iEmail_Type' => 'i.email_type',
                    'iPhone' => 'i.phone_1',
                    'iPhone_Type' => 'i.phone_type_1',
                    'associate',
                    'iConfirmed' => 'i.confirmed',
                    'iCity' => 'i.city',
                    'avatar'))
                ->joinLeft(array('r' => $this->getTable('Role')), "r.role_id = {$func}.role_id", array('role' => 'name'));


        $select->where("i.academic_area=(?)", $categoria);

        if ($associado)
            $select->where("i.associate = 1");

        if ($ativo)
            $select->where("i.confirmed = 1");

        if (!is_null($key))
            $select->where("(i.name LIKE '%$key%' OR i.email='$key')");


        if (!is_null($order))
            $select->order("{$func}.created $order");

        $all = $func->fetchAll($select);    
            
        if (!is_null($limit)) {
            $limit = explode(',', $limit);
            $select->limit((int)$limit[1],(int)$limit[0]);
        }

        $rows = $func->fetchAll($select);

        $reg = array();
        foreach ($rows as $r) {
            $reg[$r['info_id']]['nome'] = $r['name'];
            $reg[$r['info_id']]['nivel'] = $r['academic_level'];
            $reg[$r['info_id']]['atuacao'] = $r['academic_field'];
            $reg[$r['info_id']]['area'] = $c->translate($r['academic_area']);
            $reg[$r['info_id']]['cursos_palestras'] = $r['panelist'];

            $reg[$r['info_id']]['telefone'] = array();
            if ($r['phone_type_1'] == 'comercial')
                $reg[$r['info_id']]['telefone'][] = $r['phone_1'];

            if ($r['phone_type_2'] == 'comercial')
                $reg[$r['info_id']]['telefone'][] = $r['phone_2'];

            $reg[$r['info_id']]['email'] = array();
            if ($r['email_type'] == 'comercial')
                $reg[$r['info_id']]['email'][] = $r['email'];

            if ($r['email_type_2'] == 'comercial')
                $reg[$r['info_id']]['email'][] = $r['email_2'];

            /*$reg[$r['info_id']]['endereco'] = array();
            if ($r['address_type'] != 'comercial') {
                $reg[$r['info_id']]['endereco'] = array();
                $reg[$r['info_id']]['endereco'][0]['endereco'] = $r['address'];
                $reg[$r['info_id']]['endereco'][0]['numero'] = $r['number'];
                $reg[$r['info_id']]['endereco'][0]['complemento'] = $r['complement'];
                $reg[$r['info_id']]['endereco'][0]['bairro'] = $r['district'];
                $reg[$r['info_id']]['endereco'][0]['cidade'] = $r['city'];
                $reg[$r['info_id']]['endereco'][0]['estado'] = $r['state'];
                $reg[$r['info_id']]['endereco'][0]['cep'] = $r['postal_code'];
            }

            if ($r['address_type_2'] != 'comercial') {
                $reg[$r['info_id']]['endereco'] = array();
                $reg[$r['info_id']]['endereco'][1]['endereco'] = $r['address'];
                $reg[$r['info_id']]['endereco'][1]['numero'] = $r['number'];
                $reg[$r['info_id']]['endereco'][1]['complemento'] = $r['complement'];
                $reg[$r['info_id']]['endereco'][1]['bairro'] = $r['district'];
                $reg[$r['info_id']]['endereco'][1]['cidade'] = $r['city'];
                $reg[$r['info_id']]['endereco'][1]['estado'] = $r['state'];
                $reg[$r['info_id']]['endereco'][1]['cep'] = $r['postal_code'];
            }*/
        }



        if (count($reg) > 0) {
            return array('filter'=>$reg,'total'=>count($all));
        }

        return null;
    }
    
    public function getComboOptions($filter=null,$primary='user_id',$value='name'){
        $helper = $this->getHelper('DataCombo');
        return $helper->getDataCombo($primary,$value,$this->getAll($filter));
    }

}

