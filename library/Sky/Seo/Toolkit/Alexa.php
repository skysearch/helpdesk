<?php


class Sky_Seo_Toolkit_Alexa extends Sky_Seo_Toolkit_Abstract
{
	const REQUEST_URI = 'http://xml.alexa.com/data?cli=10&dat=nsa&url=%s';
	
	public function getBackLinksCount()
	{
		$url  = sprintf(self::REQUEST_URI, $this->_url);		
	    $data = Sky_Seo_Request::getResponse($url); 
	   	return 0;
	}
	
	public function getBackLinks($offset, $count)
	{		
		return array();
	}

	public function getIndexedPagesCount()
	{
		return 0;
	}
	
	public function getIndexedPages($offset, $count)
	{
		return array();		 
	}
}
