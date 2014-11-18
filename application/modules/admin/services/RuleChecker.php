<?php
/**
 * @copyright	Copyright (c) 2009-2010 TIG Corporation (http://www.tig.vn)
 * @license		http://www.gnu.org/licenses/gpl-2.0.txt GNU GENERAL PUBLIC LICENSE Version 2
 * @since		2.0.0
 */

class Admin_Services_RuleChecker 
{
	public static function isAllowed($action, $controller = null, $module = null, $callback = null, $params = null) 
	{
		if (!Zend_Auth::getInstance()->hasIdentity()) {
			return false;
		}
		$user = Zend_Auth::getInstance()->getIdentity();
		
		$action = strtolower($action);
		
		/**
		 * Get module and controller name
		 */
		if (null == $controller) {
			$controller = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
		}
		if (null == $module) {
			$module = Zend_Controller_Front::getInstance()->getRequest()->getModuleName();
		}
		
		$isAllowed = Admin_Services_Acl::getInstance()
					->isUserOrRoleAllowed($user['user']['role_id'], $user['user']['user_id'], $module, $controller, $action);
		if (!$isAllowed) {
			return false;
		}
		if (null != $callback) {
			if (false !== ($pos = strpos($callback, '::'))) {
				$callback = array(substr($callback, 0, $pos), substr($callback, $pos + 2));
			}
			return call_user_func_array($callback, $params);
		}
		return true;
	}
}
