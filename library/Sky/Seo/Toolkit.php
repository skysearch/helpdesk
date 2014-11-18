<?php


class Sky_Seo_Toolkit
{
	/**
	 * Get toolkit adapter
	 * 
	 * @param string $adapter
	 * @return Sky_Seo_Toolkit_Abstract
	 */
	public static function factory($adapter)
	{
		$adapter = strtolower($adapter);
		switch ($adapter)
		{
			case 'bing':
				return new Sky_Seo_Toolkit_Bing();
				break;
			case 'yahoo':
				return new Sky_Seo_Toolkit_Yahoo();
				break;
			case 'google':
			default:
				return new Sky_Seo_Toolkit_Google();
				break;
		}
	}	
}
