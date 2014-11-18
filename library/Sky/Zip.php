<?php
/**
 * @copyright	Copyright (c) 2009-2010 TIG Corporation (http://www.tig.vn)
 * @license		http://www.gnu.org/licenses/gpl-2.0.txt GNU GENERAL PUBLIC LICENSE Version 2
 * @version 	$Id: Zip.php 3986 2010-07-25 16:32:46Z huuphuoc $
 * @since		2.0.4
 */

class Sky_Zip 
{
	/**
	 * @param string $file
	 * @param string $tool
	 * @return Sky_Zip_Abstract
	 */
	static public function factory($file, $adapter = null)
	{
		if (null == $adapter) {
			/**
			 * Auto detect
			 */
			if (class_exists('ZipArchive')) {
				$adapter = 'ZipArchive';
			} else {
				$adapter = 'PclZip';
			}
		}
		$className = 'Sky_Zip_Adapter_' . $adapter;
		$object    = new $className($file);
		if (!$object instanceof Sky_Zip_Abstract) {
			throw new Exception($className.' is not instance of Sky_Zip_Abstract');
		}
		return $object;
	}
}
