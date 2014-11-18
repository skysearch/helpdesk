<?php

/**
 * Use Yahoo Site Explorer API:
 * http://developer.yahoo.com/search/siteexplorer/
 * 
 * The API limits 5,000 queries per IP address per day
 * http://developer.yahoo.com/search/rate.html
 * 
 * Follow the guides at http://developer.yahoo.com/wsregapp/ to get API key
 */
class Sky_Seo_Toolkit_Yahoo extends Sky_Seo_Toolkit_Abstract
{
	/**
	 * @const string
	 */
	const REQUEST_URI = 'http://search.yahooapis.com/SiteExplorerService/V1/';
	
	/**
	 * @see http://developer.yahoo.com/search/siteexplorer/V1/inlinkData.html
	 */
	public function getBackLinksCount()
	{
		$url = self::_buildUrl('inlinkData', array(
			'appid' 	  => $this->_apiKey,
			'query' 	  => $this->_url,
			'entire_site' => 1,
			'results' 	  => 1,
			'output' 	  => 'json',	
		));
		$content = Sky_Seo_Request::getResponse($url);
		$results = Zend_Json::decode($content);		
		if (!isset($results['ResultSet']['totalResultsAvailable'])) {
			return 0;	
		}
		return $results['ResultSet']['totalResultsAvailable'];
	}

	/**
	 * @see http://developer.yahoo.com/search/siteexplorer/V1/inlinkData.html
	 */
	public function getBackLinks($offset, $count)
	{
		/**
		 * Note that with Yahoo Site Explorer, $offset has to be greater than or equal 1
		 */
		if (0 == $offset) {
			$offset = 1;
		}
		$url = self::_buildUrl('inlinkData', array(
			'appid'   => $this->_apiKey,
			'query'   => $this->_url,
			'start'   => $offset,
			'results' => $count,
			'output'  => 'json',	
		));
		$content = Sky_Seo_Request::getResponse($url);
		$results = Zend_Json::decode($content);
		return self::_convertResults($results);
	}

	/**
	 * @see http://developer.yahoo.com/search/siteexplorer/V1/pageData.html
	 */
	public function getIndexedPagesCount()
	{
	 	$url = self::_buildUrl('pageData', array(
			'appid'  => $this->_apiKey,
			'query'  => $this->_url,
	 		'output' => 'json',
		));	
		$content = Sky_Seo_Request::getResponse($url);
		$results = Zend_Json::decode($content);
		if (!isset($results['ResultSet']['totalResultsAvailable'])) {
			return 0;	
		}
		return $results['ResultSet']['totalResultsAvailable'];
	}
	
	/**
	 * @see http://developer.yahoo.com/search/siteexplorer/V1/pageData.html
	 */
	public function getIndexedPages($offset, $count)
	{
		/**
		 * Note that with Yahoo Site Explorer, $offset has to be greater than or equal 1
		 */
		if (0 == $offset) {
			$offset = 1;
		}		  
		$url = self::_buildUrl('pageData', array(
			'appid'   => $this->_apiKey,
			'query'   => $this->_url,
			'start'   => $offset,
			'results' => $count,
			'output'  => 'json',	
		));  
		$content = Sky_Seo_Request::getResponse($url);
		$results = Zend_Json::decode($content);
		return self::_convertResults($results);
	}

	private static function _buildUrl($typeData, $params) {
		$url = self::REQUEST_URI . $typeData . '?' . http_build_query($params);
		return $url;
	}
	
	private static function _convertResults($results) {
		if (!isset($results['ResultSet']['Result'])) {
			return array();
		}
		$links = array();
		foreach ($results['ResultSet']['Result'] as $result) {
			$links[] = array(
				'title' 	  => $result['Title'],
				'description' => null,
				'url' 		  => $result['Url'],
				'displayUrl'  => $result['ClickUrl'],
				'cacheUrl' 	  => null,
			);
		}
		
		return $links;					
	}
}
