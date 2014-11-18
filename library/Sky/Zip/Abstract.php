<?php
/**
 * @copyright	Copyright (c) 2009-2010 TIG Corporation (http://www.tig.vn)
 * @license		http://www.gnu.org/licenses/gpl-2.0.txt GNU GENERAL PUBLIC LICENSE Version 2
 * @version 	$Id: Abstract.php 3349 2010-06-28 06:14:27Z huuphuoc $
 * @since		2.0.4
 */

abstract class Sky_Zip_Abstract
{
	/**
	 * @var string
	 */
	protected $_file;
	
	public function __construct($file)
	{
		$this->_file = $file;
	}
	
	abstract public function open();
	
	abstract public function close();
	
	/**
	 * Extract to destination directory
	 * @param string $destination
	 */
	abstract public function extract($destination);
}
