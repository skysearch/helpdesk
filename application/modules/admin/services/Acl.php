<?php

/**
 * @copyright	Copyright (c) 2009-2010 TIG Corporation (http://www.tig.vn)
 * @license		http://www.gnu.org/licenses/gpl-2.0.txt GNU GENERAL PUBLIC LICENSE Version 2
 * @since		2.0.0
 */

/**
 * Idea and most implementions here was taken from
 * http://www.phpclasses.org/browse/package/4100.html
 */
class Admin_Services_Acl extends Zend_Acl {

    /**
     * @var Core_Services_Acl
     */
    private static $_instance = null;

    /**
     * @return Core_Services_Acl
     */
    public static function getInstance() {
        if (null == self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct() {
//		ini_set('max_execution_time', 120);

        $this->_buildResources();
        $this->_buildRoles();
        $this->_buildRules();
    }

    /**
     * Create the resources
     */
    private function _buildResources() {
        $acl = Sky_Model_Factory::getInstance()->setModule('admin')->getAcl();

        $resources = $acl->getResources();

        if (0 == count($resources)) {
            return;
        }

        $allResources = array();
        /**
         * Map resource id to its name
         */
        $map = array();
        foreach ($resources as $row) {
            $allResources[] = $row['resource_id'];
            $map[$row['resource_id']] = $row['module_name'] . ':' . $row['controller_name'];
        }


        $numResources = count($resources);
        $i = 0;
        while ($numResources > $i) {
            foreach ($resources as $row) {
                /**
                 * Check if parent resource (if any) exists
                 * Only add if this resource hasn't yet been added and its parent is known, if any
                 */
                $resId = $row['module_name'] . ':' . $row['controller_name'];

                if (!$this->has($resId)) {
                    $this->addResource(new Zend_Acl_Resource($resId));
                    $i++;
                }
            }
        }
    }

    /**
     * Create roles
     */
    private function _buildRoles() {

        $roles = Sky_Model_Factory::getInstance()->setModule('admin')->getAcl()->getRoles();

        if (0 == count($roles)) {
            $rs = array();
        }

        /**
         * Build map from role Id to role identifier (defined by function _formatRole)
         */
        $map = array();
        foreach ($roles as $role) {
            $map[$role['role_id']] = $this->_formatRole($role['role_id']);
        }

        /**
         * Create an array that stores all roles and their parents
         */
        $roles = array();
        foreach ($roles as $role) {
            if (!isset($roles[$this->_formatRole($role['role_id'])])) {
                $roles[$this->_formatRole($role['role_id'])] = array();
            }
        }

        /**
         * Now add to the ACL
         */
        $numRoles = count($roles);
        $i = 0;

        /**
         * While there are still elements left to be added
         */
        while ($numRoles > $i) {
            /**
             * Check every element in the db
             */
            foreach ($roles as $role => $parentRoles) {
                /**
                 * Check if a parent is invalid to prevent an infinite loop
                 * if the relational DBase works, this shouldn't happen
                 */
                foreach ($parentRoles as $childRole) {
                    if (!array_key_exists($childRole, $roles)) {
                        throw new Zend_Acl_Exception('Role id "' . $childRole . '" does not exist');
                    }
                }
                /**
                 * If it has not yet been added to the ACL
                 * and no parents exist or
                 * we know them all
                 */
                if (!$this->hasRole($role) &&
                        (empty($parentRoles) ||
                        $this->_hasAllRolesOf($parentRoles))) {
                    /**
                     * We can add to ACL
                     */
                    $this->addRole(new Zend_Acl_Role($role), $parentRoles);
                    $i++;
                }
            }
        }
    }

    /**
     * Create rules
     */
    private function _buildRules() {
        $rules = Sky_Model_Factory::getInstance()->setModule('admin')->getAcl()->getRules();

        if (count($rules) > 0) {
            foreach ($rules as $row) {
                if (!$this->hasRole($row['role_id'])) {
                    $this->addRole(new Zend_Acl_Role($row['role_id']));
                }
                if ($row->allow == true) {
                    $this->allow($row['role_id'], $row['resource_name'], $row['privilege_name']);
                } else {
                    $this->deny($row['role_id'], $row['resource_name'], $row['privilege_name']);
                }
            }
        }
    }

    public function isUserOrRoleAllowed($roleId, $userId, $module, $controller, $action = null) {
        if ($action != null) {
            $action = strtolower($action);
        }
        $resource = strtolower($module . ':' . $controller);

        /**
         * If the resource don't exist
         */
        if (!$this->has($resource)) {
            return false;
        }

        $roleId = $this->_formatRole($roleId);
        $userId = $userId;
        if (($this->hasRole($roleId) && $this->isAllowed($roleId, $resource, $action))
                || ($this->hasRole($userId) && $this->isAllowed($userId, $resource, $action))) {
            return true;
        }
        return false;
    }

    /**
     * @param array $searchRoles
     * @return bool
     */
    private function _hasAllRolesOf($searchRoles) {
        foreach ($searchRoles as $role) {
            if (!$this->hasRole($role)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Generate the role name which will be added to ACL based on the original role Id
     * 
     * @param string $roleId The role Id
     * @return string
     */
    private function _formatRole($roleId) {
        return $roleId;
    }

}
