<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Sky_File {
    /**
	 * @param string $file
	 * @param string $tool
	 * @return Sky_Zip_Abstract
	 */
	static public function factory($method,$configs=null)
	{
		array('upload','download');
                
		$className = 'Sky_File_' . ucfirst(strtolower($method));
		$object    = new $className($configs);
		if (!is_object($object)) {
			throw new Exception($className.' is not object of Sky_File.');
		}
		return $object;
	}
}
