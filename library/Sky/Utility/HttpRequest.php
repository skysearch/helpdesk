<?php
/**
 * @copyright	Copyright (c) 2009-2010 TIG Corporation (http://www.tig.vn)
 * @license		http://www.gnu.org/licenses/gpl-2.0.txt GNU GENERAL PUBLIC LICENSE Version 2
 * @version 	$Id: HttpRequest.php 3986 2010-07-25 16:32:46Z huuphuoc $
 * @since		2.0.0
 */

class Sky_Utility_HttpRequest 
{
	public static function getResponse($url, $method = Zend_Http_Client::GET, $timeout = 300) 
	{
		try {
			$request = new Zend_Http_Client();
			$request->setConfig(array('timeout' => $timeout));
			$request->setUri($url);
			$request->setMethod($method);
			$content = $request->request()->getBody();
			return $content;
		} catch (Exception $ex) {
			/**
			 * Could not connect to $url 
			 */
			return null;
		}
	}
}
