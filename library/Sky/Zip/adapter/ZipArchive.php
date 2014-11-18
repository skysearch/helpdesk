<?php
/**
 * @copyright	Copyright (c) 2009-2010 TIG Corporation (http://www.tig.vn)
 * @license		http://www.gnu.org/licenses/gpl-2.0.txt GNU GENERAL PUBLIC LICENSE Version 2
 * @version 	$Id: ZipArchive.php 3348 2010-06-28 06:14:09Z huuphuoc $
 * @since		2.0.4
 */

class Sky_Zip_Adapter_ZipArchive extends Sky_Zip_Abstract
{
	/**
	 * @var ZipArchive
	 */
	private $_zip;
	
	public function __construct($file)
	{
		parent::__construct($file);
		$this->_zip = new ZipArchive();
	}
	
	public function open()
	{
		return $this->_zip->open($this->_file);
	}
	
	public function close()
	{
		$this->_zip->close();
	}
	
	/**
	 * Extract to destination directory
	 * @param string $destination
	 */
	public function extract($destination)
	{
		$this->_zip->extractTo($destination);	
	}
}
