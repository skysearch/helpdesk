<?php

class Sky_Cache 
{
	/**
	 * Get global cache instance
	 * 
	 * @return Zend_Cache_Core
	 */
	public static function getInstance() 
	{
		$config = Sky_Config::getConfig();
		if (!isset($config->cache->frontend) || !isset($config->cache->backend)) {
			return null;
		}
		$frontendOptions = $config->cache->frontend->options->toArray();
		$backendOptions  = $config->cache->backend->options->toArray();
		$frontendOptions = self::_replaceConst($frontendOptions);
		$backendOptions  = self::_replaceConst($backendOptions);
		
		return Zend_Cache::factory($config->cache->frontend->name, $config->cache->backend->name,
			$frontendOptions, $backendOptions);
	}
	
	private static function _replaceConst($options) 
	{
		$search 	= array('{DS}', '{TEMP_PATH}');
		$replace 	= array(DS, TEMP_PATH);
		$newOptions = array();
		foreach ($options as $key => $value) {
			$newOptions[$key] = str_replace($search, $replace, $value);
		}
		return $newOptions;
	}
}
