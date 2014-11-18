<?php

class Sky_Seo_Request
{
	/**
	 * Get response from search engine
	 * 
	 * @param string $url The URL
	 * @param int $timeout The connection timeout (in seconds)
	 * @return string
	 */
	public static function getResponse($url, $timeout = 10)
	{
		if (function_exists('curl_init')) {
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			if ((ini_get('open_basedir') == '') && (ini_get('safe_mode') == 'Off')) {
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			}
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			curl_setopt($ch, CURLOPT_FAILONERROR, 1);
			return @curl_exec($ch);
		} else {
			return @file_get_contents($url);
		}
	}
}
