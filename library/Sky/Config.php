<?php
/**
 * @copyright	Copyright (c) 2009-2010 TIG Corporation (http://www.tig.vn)
 * @license		http://www.gnu.org/licenses/gpl-2.0.txt GNU GENERAL PUBLIC LICENSE Version 2
 * @version 	$Id: Config.php 3986 2010-07-25 16:32:46Z huuphuoc $
 * @since		2.0.0
 */

class Sky_Config 
{
	const KEY = 'Sky_Config_';
	
	/**
	 * Get application config object
	 * 
	 * @return Zend_Config
	 */
	public static function getConfig() 
	{
		$key = self::KEY;
		if (!Zend_Registry::isRegistered($key)) {
			$defaultConfig = APPLICATION_PATH . DS . 'configs' . DS . 'application.ini';
			
			$config = new Zend_Config_Ini($defaultConfig,APPLICATION_ENV);
			Zend_Registry::set($key, $config);
		}
		
		return Zend_Registry::get($key);
	}
}
