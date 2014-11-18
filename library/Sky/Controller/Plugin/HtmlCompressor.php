<?php
/**
 * @copyright	Copyright (c) 2009-2010 TIG Corporation (http://www.tig.vn)
 * @license		http://www.gnu.org/licenses/gpl-2.0.txt GNU GENERAL PUBLIC LICENSE Version 2
 * @version 	$Id: HtmlCompressor.php 3349 2010-06-28 06:14:27Z huuphuoc $
 * @since		2.0.6
 */

/**
 * Compress HTML
 */
class Sky_Controller_Plugin_HtmlCompressor extends Zend_Controller_Plugin_Abstract
{
	public function dispatchLoopShutdown()
	{
		$response = $this->getResponse();
		$body     = $response->getBody();
		$body     = Sky_Utility_HtmlCompressor::compress($body);
		$response->setBody($body);
	}
}
