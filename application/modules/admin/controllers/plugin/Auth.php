<?php

/**
 * TomatoCMS
 * 
 * LICENSE
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE Version 2 
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-2.0.txt
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@tomatocms.com so we can send you a copy immediately.
 * 
 * @copyright	Copyright (c) 2009-2010 TIG Corporation (http://www.tig.vn)
 * @license		http://www.gnu.org/licenses/gpl-2.0.txt GNU GENERAL PUBLIC LICENSE Version 2
 * @version 	$Id: Auth.php 4786 2010-08-23 12:04:41Z huuphuoc $
 * @since		2.0.0
 */

/**
 * Base on the request URL and role/permisson of current user, forward the user
 * to the login page if the user have not logged in 
 */
class Admin_Controllers_Plugin_Auth extends Zend_Controller_Plugin_Abstract {

    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        $configs = Sky_Config::getConfig();
        $module = $request->getModuleName();
        $controller = $request->getControllerName();
        $action = $request->getActionName();

        $layout = ROOT_PATH . DS . 'layouts' . DS . 'scripts' . DS . 'admin' . DS . $configs->web->backend->layout;
        
        if ($module === 'default') {
            return;
        }

        

        /**
         * Switch to admin template
         * @since 2.0.4
         */
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        if (null === $viewRenderer->view) {
            $viewRenderer->initView();
        }
        $view = $viewRenderer->view;
        $view->assign('APP_SKIN', $configs->web->backend->skin);
        $view->setScriptPath($layout. DS .  'views' . DS . $module . DS . 'scripts');
        
        Zend_Layout::startMvc(array('layoutPath' => $layout));
        Zend_Layout::getMvcInstance()->setLayout('default');

        $isAllowed = false;
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $user = Zend_Auth::getInstance()->getIdentity();
            

            /**
             * Add 'core:message' resource that allows show the friendly error message
             */
            $acl = Admin_Services_Acl::getInstance();

            if (!$acl->has('admin:message')) {
                $acl->addResource('admin:message');
            }

            $isAllowed = $acl->isUserOrRoleAllowed($user['user']['role_id'], $user['user']['user_id'], $module, $controller, $action);
        }
        
        if($controller == 'auth') {
            $isAllowed = true;
        }
        
        if (!$isAllowed) {

            $view->setScriptPath($layout. DS .  'views' . DS . 'admin' . DS . 'scripts');
            
            $forwardAction = Zend_Auth::getInstance()->hasIdentity() ? 'deny' : 'login';

            /**
             * DON'T use redirect! as folow:
             * $this->getResponse()->setRedirect('/Login/');
             */
            
            $request->setModuleName('admin')
                    ->setControllerName('auth')
                    ->setActionName($forwardAction)
                    ->setDispatched(true);
        }
    }

}
