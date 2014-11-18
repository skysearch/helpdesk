<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Acl
 *
 * @author Renato David
 */
class Application_Model_Default_Repository_Admin_Acl extends Sky_Db_Repository_Abstract {
    

    /*
     * Return all roles
     * @return Zend_Table_Rowset
     */
    public function getRoles($where=null){
        return $this->getTable('Role')->fetchAll($where);
    }
    
    /*
     * Return roles by name
     * @return Zend_Table_Rowset
     */
    public function getRoleByName($name){
        $role = $this->getTable('Role');
        $select = $role->select()->where('name=(?)',$name);
        
        return $role->fetchRow($select);
    }
    
    /*
     * Create new role
     * @param array $data
     * @return integer $id
     */
    public function insertRole($data){
        return $this->getTable('Role')->insert($data);
    }
    
    
    /*
     * Return role by id
     * @return Zend_Table_Row
     */
    public function getRoleById($id){
        return $this->getTable('Role')->find($id)->current();
    }
    
    /*
     * Delete rules by role
     * @param integer $id
     * @return bool
     */
    public function deleteRulesByRole($id){
        $rule = $this->getTable('Rule');
        return (bool) $rule->delete(array('role_id=(?)'=>$id));
    }
    
    
    /*
     * Delete rules from roles
     * @param integer $id
     * @return bool
     */
    public function deleteRole($id){
        $role = $this->getRoleById($id);
        
        if($role['locked'])
            return false;
        
        return (bool) $role->delete();
    }
    
    
    /*
     * Return all rules
     * @return Zend_Table_Rowset
     */
    public function getRules(){
        $rule = $this->getTable('Rule');
        $select = $rule->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                              ->setIntegrityCheck(false)
                              //->from(array('r'=>$rule),array('role_id','privilege_id','allow','resource_name'))
                              ->joinLeft(array('p'=>$this->getTable('Privilege')),"{$rule}.privilege_id=p.privilege_id",array('privilege_name'=>'name'));
        return $rule->fetchAll($select);
    }
    
    
    /*
     * Return rules from role
     * @param integer $id
     * @return Zend_Table_Rowset
     */
    public function getRulesByRole($id){
        $rule = $this->getTable('Rule');
        $select = $rule->select()->where('role_id=(?)',$id);
        return $rule->fetchAll($select);
    }
    
    
    public function updateRole($data,$id){
        $role =$this->getTable('Role');
        return (bool) $role->update($data,array('role_id=(?)'=>$id));
    }




    /*
     * Create new role
     * @param array $data
     * @return integer $id
     * $privilege = array(
     *      0=>array(privilege=>int value,allow=>int value)
     * )
     */
    public function insertRule($privilege,$role,$allow=null){
        $rule = $this->getTable('Rule');
        
        $this->deleteRulesByRole($role);
        
        if(is_array($privilege)) {
            $id = array();

            foreach ($privilege as $pr) {
                if(!is_array($pr)) continue;
                
                foreach ($pr as $prv){
                    $row = $this->getPrivilegeById($prv);
                    if(count($row)>0){
                        $id[] = $rule->insert(array('privilege_id'=>$row['privilege_id'],'role_id'=>$role,'allow'=>$allow,'resource_name'=>$row['module_name'] . ":" . $row['controller_name']));
                    }
                }
            }

            return $id;
        }
        
        $row = $this->getPrivilege($privilege);
        if(count($row)>0)
            return $rule->insert(array('privilege_id'=>$row['privilege_id'],'role_id'=>$role,'allow'=>$allow,'resource_name'=>$row['module_name'] . ":" . $row['controller_name']));
    
        
        return null;
    }
    
    /*
     * Return all resources
     * @return Zend_Table_Rowset
     */
    public function getResources($where=null){
        return $this->getTable('Resource')->fetchAll($where);
    }
    
    /*
     * Return resources by nav
     * @return Zend_Table_Rowset
     */
    public function getResourcesByNav($nav){
        $resource = $this->getTable('Resource');
        $select = $resource
                       ->select()
                       ->where('nav=(?)',$nav)
                       ->where('is_visible=(?)',1)
                       ->order('order ASC');
        
        return $resource->fetchAll($select);
    }
    

    /*
     * Create new resource
     * @param array $data
     * @return integer $id
     */
    public function insertResource($data){
        return $this->getTable('Resource')->insert($data);
    }
    
    /*
     * Return all privileges in array
     * @return array $values
     */
    public function getPrivileges($where=null){
        
        $rows = $this->getTable('Privilege')->fetchAll($where,'order ASC');
        
        $values = array();
        
        foreach ($rows as $row) {
            $values[$row['module_name']][$row['controller_name']][$row['name']] = $row['description'];
        }
        
        if(count($values)>0) {
            return $values;
        }
        
        return null;
    }
    
    
   

    /*
     * Return all privileges by resource
     * @return @return Zend_Db_Table_Rowset
     */
    public function getPrivilegesByResource($module,$controller){
        $privileges = $this->getTable('Privilege');
        $select = $privileges->select()
                            ->where('module_name=(?)',$module)
                            ->where('controller_name=(?)',$controller)
                            ->order('description ASC');
       
        $rows = $privileges->fetchAll($select);
        
        return $rows;
    }
    
    /*
     * Return privilege in object
     * @return Zend_Db_Table_Row
     */
    public function getPrivilegeById($id){
        return $this->getTable('Privilege')->find($id)->current();
        
    }
    
    public function getToDataTable($post, $columns) {
        $role = $this->getTable('Role');
        $helper = $this->getHelper('DataTable');

        $select = $role->select();

        $helper->setColumns($columns);

        return $helper->getJsonDataTable($role, $select, $post,'role_id',array('del','edit'));
    }
    
    public function roleExist($name){
        $role = $this->getTable('Role');
        $role = $role->fetchRow(array('name=(?)'=>$name));
        
        if(count($role)>0) return true;
        
        return false;
    }
    
    
    public function isUserInRole($name){
        $auth = Zend_Auth::getInstance();
        
        if(!$auth->hasIdentity())
            return null;
        
        $info = Zend_Auth::getInstance()->getIdentity();
        $role = $this->getRoleByName($name);
       
        //Zend_Debug::dump($info);
        
        if($info['user']['role_id'] == $role['role_id']) {
            return true;
        }
        
        return false;
    }
}
