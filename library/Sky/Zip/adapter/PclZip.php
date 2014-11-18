<?php
/**
 * @copyright	Copyright (c) 2009-2010 TIG Corporation (http://www.tig.vn)
 * @license		http://www.gnu.org/licenses/gpl-2.0.txt GNU GENERAL PUBLIC LICENSE Version 2
 * @version 	$Id: PclZip.php 3348 2010-06-28 06:14:09Z huuphuoc $
 * @since		2.0.4
 */

class Sky_Zip_Adapter_PclZip extends Sky_Zip_Abstract
{
	/**
	 * @var PclZip
	 */
	private $_zip;
	
	public function __construct($file)
	{
		parent::__construct($file);
		require_once 'pclzip/pclzip.lib.php';
		$this->_zip = new PclZip($file);
	}
	
	public function open()
	{
		return true;
	}
	
	public function close()
	{
	}
	
	/**
	 * Extract to destination directory
	 * @param string $destination
	 */
	public function extract($destination)
	{
		$this->_zip->extract(PCLZIP_OPT_PATH, $destination);	
	}
}
