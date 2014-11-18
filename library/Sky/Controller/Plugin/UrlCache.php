<?php
/**
 * @copyright	Copyright (c) 2009-2010 TIG Corporation (http://www.tig.vn)
 * @license		http://www.gnu.org/licenses/gpl-2.0.txt GNU GENERAL PUBLIC LICENSE Version 2
 * @version 	$Id: UrlCache.php 5451 2010-09-16 08:23:32Z huuphuoc $
 * @since		2.0.0
 */

class Sky_Controller_Plugin_UrlCache extends Zend_Controller_Plugin_Abstract 
{
	public function preDispatch(Zend_Controller_Request_Abstract $request) 
	{
		$router    = Zend_Controller_Front::getInstance()->getRouter();
		$routeName = $router->getCurrentRouteName();
		$currRoute = $router->getCurrentRoute();
		
		/**
		 * Do NOT cache if we are in back-end section
		 */
		if (Zend_Layout::getMvcInstance() != null 
			&& 'admin' == Zend_Layout::getMvcInstance()->getLayout()) 
		{
			return;
		}
		
		$file = APPLICATION_PATH . DS . 'config' . DS . 'cache.ini';
		if (!file_exists($file) 
			|| (($config = new Zend_Config_Ini($file, 'cache')) == null)) 
		{
			return;
		}
		$array = $config->toArray();
		if (!isset($array['routes'][$routeName]['cache']['enable']) 
			|| 'false' == $array['routes'][$routeName]['cache']['enable']) 
		{
			return;
		}
		
		$file = null;
		if ($currRoute instanceof Zend_Controller_Router_Route_Static) {
			$file = $routeName;
		} elseif ($currRoute instanceof Zend_Controller_Router_Route_Regex) {
			$file = $routeName;

			/**
			 * Build file names based on parameters
			 */
			$params = $request->getParams();
			$file  .= '_' . serialize($params);
		}
			
		$file = md5($file);
		
		/**
		 * Add template as file prefix
		 * Because the user can browse by PC or mobile device which the templates are different
		 * hence the cache version for same URL should be difference
		 */
		$template = (Zend_Registry::isRegistered('APP_TEMPLATE') 
					&& Zend_Registry::get('APP_TEMPLATE') != null)
					? Zend_Registry::get('APP_TEMPLATE') : '';
		$file     = $template . '_' . $routeName . '_' . $file;
		$lifetime = $array['routes'][$routeName]['cache']['lifetime'];
		if (Sky_Cache_File::isCached(Sky_Cache_File::CACHE_URL,
			$file, $lifetime)
		) {
			$request->setModuleName('core')
					->setControllerName('Cache')
					->setActionName('html')
					->setParam('__cacheType', Sky_Cache_File::CACHE_URL)
					->setParam('__cacheKey', $file)
					->setDispatched(true);
		} else {
			/**
			 * Continue action and assign flag to save output to cache later
			 */
			$request->setParam('__isCacheURL', true)
					->setParam('__key', $file);
		}
	}
	
	public function dispatchLoopShutdown() 
	{
		$request = $this->getRequest();
		if ($request->getParam('__isCacheURL') == true) {
			$key     = $request->getParam('__key');
			$content = $this->getResponse()->getBody()
						. '<!-- cached version at ' . date('Y-m-d H:i:s') . ' -->';
			Sky_Cache_File::cache(Sky_Cache_File::CACHE_URL, $key, $content);
		}
	}
}