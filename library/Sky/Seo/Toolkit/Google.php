<?php

/**
 * For using, registry an API key at
 * http://code.google.com/apis/ajaxsearch/signup.html
 */
class Sky_Seo_Toolkit_Google extends Sky_Seo_Toolkit_Abstract
{
	/**
	 * @see http://code.google.com/apis/ajaxsearch/documentation/reference.html#_intro_fonje
	 * @const string
	 */
	const REQUEST_URI = 'http://ajax.googleapis.com/ajax/services/search/web';
	
	public function getBackLinksCount()
	{
		$url = self::_buildUrl(array(
			'v' 	 => '1.0',
			'filter' => 0,
			'key'    => $this->_apiKey,
			'q' 	 => 'link:' . $this->_url,	
		));
		$content = Sky_Seo_Request::getResponse($url);
		$results = Zend_Json::decode($content);
		if (!isset($results['responseData']['cursor']['estimatedResultCount'])) {
			return 0;
		}
		return $results['responseData']['cursor']['estimatedResultCount'];
	}
	
	public function getBackLinks($offset, $count)
	{
		/**
		 * Google Ajax API does not allow to retire more than 8 records
		 * @see http://code.google.com/apis/ajaxsearch/documentation/reference.html#_intro_fonje
		 */
		$count = min(array($count, 8));
		
		$params = array(
			'v' 	=> '1.0',
			'q' 	=> 'link:' . $this->_url,
			'start' => $offset,
			'rsz' 	=> $count,
		);
		if ($this->_apiKey) {
			$params['key'] = $this->_apiKey;
		}
		
		$url = self::_buildUrl($params);	
		$content = Sky_Seo_Request::getResponse($url);
		$results = Zend_Json::decode($content);
		return self::_convertResults($results);
	}

	public function getIndexedPagesCount()
	{
		$url = self::_buildUrl(array(
			'v' 	 => '1.0',
			'filter' => 0,
			'key'    => $this->_apiKey,
			'q' 	 => 'site:' . $this->_url,
		));
		$content = Sky_Seo_Request::getResponse($url);
		$results = Zend_Json::decode($content);
		if (!isset($results['responseData']['cursor']['estimatedResultCount'])) {
			return 0;
		}
		return $results['responseData']['cursor']['estimatedResultCount'];
	}
	
	public function getIndexedPages($offset, $count)
	{
		/**
		 * Google Ajax API does not allow to retire more than 8 records
		 * @see http://code.google.com/apis/ajaxsearch/documentation/reference.html#_intro_fonje
		 */
		$count = min(array($count, 8));
		
		$url = self::_buildUrl(array(
			'v' 	=> '1.0',
			'key'   => $this->_apiKey,
			'q' 	=> 'site:' . $this->_url,
			'start' => $offset,
			'rsz' 	=> $count,
		));
		$content = Sky_Seo_Request::getResponse($url);
		$results = Zend_Json::decode($content);
		return self::_convertResults($results);
	}
	
	private static function _buildUrl($params) {
		$url = self::REQUEST_URI . '?' . http_build_query($params);
		return $url;
	}
	
	private static function _convertResults($results) {
		if (!isset($results['responseData']['results'])) {
			return array();
		}
		$links = array();
		foreach ($results['responseData']['results'] as $result) {
			$links[] = array(
				'title' 	  => $result['titleNoFormatting'],
				'description' => $result['content'],
				'url' 		  => $result['url'],
				'displayUrl'  => $result['visibleUrl'],
				'cacheUrl' 	  => isset($result['cacheUrl']) ? $result['cacheUrl'] : null,
			);
		}
		
		return $links;
	}	
}
