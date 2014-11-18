<?php
/**
 * @copyright	Copyright (c) 2009-2010 TIG Corporation (http://www.tig.vn)
 * @license		http://www.gnu.org/licenses/gpl-2.0.txt GNU GENERAL PUBLIC LICENSE Version 2
 * @version 	$Id: ActionCache.php 5451 2010-09-16 08:23:32Z huuphuoc $
 * @since		2.0.0
 */

class Sky_Controller_Plugin_ActionCache extends Zend_Controller_Plugin_Abstract 
{
	public function preDispatch(Zend_Controller_Request_Abstract $request) 
	{
		$file = APPLICATION_PATH . DS . 'config' . DS . 'cache_action.ini';
		if (!file_exists($file)) {
			return;
		}
		$config = new Zend_Config_Ini($file, 'actions');
		$config = $config->toArray();

		foreach ($config as $key => $value) {
			/**
			 * Meet the module/controller/action
			 */
			if ($value['module'] == $request->getModuleName() 
				&& $value['controller'] == $request->getControllerName() 
				&& $value['action'] == $request->getActionName()
			) {
				if (Sky_Cache_File::isCached(Sky_Cache_File::CACHE_ACTION, 
					$key, $value['lifetime'])
				) {
					$request->setModuleName('core')
							->setControllerName('Cache')
							->setActionName('html')
							->setParam('__cacheType', Sky_Cache_File::CACHE_ACTION)
							->setParam('__cacheKey', $key)
							->setDispatched(true);
				} else {
					/**
					 * Continue action and assign flag to save output to cache later
					 */
					$request->setParam('__isCacheAction', true)
							->setParam('__key', $file);
				}
				/**
				 * Exit the loop
				 */
				return;
			}
		}
	}
	
	public function postDispatch(Zend_Controller_Request_Abstract $request) 
	{
		if ($request->getParam('__isCacheAction') == true) {
			$key = $request->getParam('__key');
			$content = $this->getResponse()->getBody()
						. '<!-- cached version from ' . date('Y-m-d H:i:s') . ' -->';
			
			Sky_Cache_File::cache(Sky_Cache_File::CACHE_ACTION, $key, $content);
		}
	}
}
