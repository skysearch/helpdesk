<?php
/**
 * @copyright	Copyright (c) 2009-2010 TIG Corporation (http://www.tig.vn)
 * @license		http://www.gnu.org/licenses/gpl-2.0.txt GNU GENERAL PUBLIC LICENSE Version 2
 * @version 	$Id: MagicQuote.php 3762 2010-07-17 12:25:01Z huuphuoc $
 * @since		2.0.3
 */

class Sky_Controller_Plugin_MagicQuote extends Zend_Controller_Plugin_Abstract 
{
	public function preDispatch(Zend_Controller_Request_Abstract $request) 
	{
		/**
		 * On shared hosting, put setting
		 * php_flag magic_quotes_gpc Off
		 * in the .htaccess file may cause 500 Server Error on server.
		 * The following snippet disable magic_quotes_gpc at runtime.
		 * 
		 * @see http://us.php.net/manual/en/security.magicquotes.disabling.php
		 */
		if (get_magic_quotes_gpc()) {
		    $process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
		    while (list($key, $val) = each($process)) {
		        foreach ($val as $k => $v) {
		            unset($process[$key][$k]);
		            if (is_array($v)) {
		                $process[$key][stripslashes($k)] = $v;
		                $process[] = &$process[$key][stripslashes($k)];
		            } else {
		                $process[$key][stripslashes($k)] = stripslashes($v);
		            }
		        }
		    }
		    unset($process);
		}		
	}
}
