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
 * @version 	$Id: Init.php 5023 2010-08-28 09:36:48Z huuphuoc $
 * @since		2.0.0
 */

class Admin_Controllers_Plugin_Init extends Zend_Controller_Plugin_Abstract 
{

    public function preDispatch(Zend_Controller_Request_Abstract $request) 
	{
		$config = Sky_Config::getConfig();
                $module = $request->getModuleName();
                
		$layout = ROOT_PATH . DS . 'layouts' . DS . 'scripts' . DS .  'frontend' . DS . $config->web->frontend->layout;
                
                
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
		if (null === $viewRenderer->view) {
			$viewRenderer->initView();
		}
                
		$view = $viewRenderer->view;
		$view->addHelperPath(LIBRARY_PATH . DS . 'Sky' . DS . 'View' . DS . 'Helper', 'Sky_View_Helper');
		$view->addHelperPath(APPLICATION_PATH . DS . 'modules' . DS . 'admin' . DS . 'views' . DS . 'helpers', 'Admin_View_Helper');
                $view->addHelperPath(APPLICATION_PATH . DS . 'modules' . DS . $module . DS . 'views' . DS . 'helpers', ucfirst(strtolower($module)).'_View_Helper');
		$view->setScriptPath($layout. DS .  'views' . DS . $module . DS . 'scripts');
		
                Zend_Locale::setDefault($config->resources->locale->default);

		/**
		 * Set layout
		 */
		Zend_Layout::startMvc(array('layoutPath' => $layout));
		Zend_Layout::getMvcInstance()->setLayout('default');
                               
	}
        

        
}
