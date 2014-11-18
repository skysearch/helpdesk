<?php
/**
 * @copyright	Copyright (c) 2009-2010 TIG Corporation (http://www.tig.vn)
 * @license		http://www.gnu.org/licenses/gpl-2.0.txt GNU GENERAL PUBLIC LICENSE Version 2
 * @version 	$Id: Admin.php 4442 2010-08-10 17:44:43Z huuphuoc $
 * @since		2.0.0
 */

class Sky_Controller_Plugin_Admin extends Zend_Controller_Plugin_Abstract 
{
	public function preDispatch(Zend_Controller_Request_Abstract $request) 
	{
		$uri = $request->getRequestUri();
		$uri = rtrim($uri, '/');
		if (strpos(strtolower($uri) . '/', '/admin/') === false) {
			return;
		}
		
		/**
		 * Auto switch to admin layout
		 */
		if (Zend_Layout::getMvcInstance() != null) {
			Zend_Layout::getMvcInstance()->setLayout('admin');
		}
	}
}
