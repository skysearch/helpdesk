<?php

abstract class Sky_Seo_Toolkit_Abstract
{
	/**
	 * The API key
	 * @var string
	 */
	protected $_apiKey;
	
	/**
	 * The URL
	 * @var string
	 */
	protected $_url;
	
	/**
	 * The connection timeout
	 * @var int
	 */
	protected $_timeout = 10;

	/**
	 * Set API key
	 * 
	 * @param string $apiKey
	 * @return Sky_Seo_Toolkit_Abstract
	 */
	public function setApiKey($apiKey)
	{
		$this->_apiKey = $apiKey;
		return $this;
	}
	
	/**
	 * Set URL
	 * 
	 * @param string $url
	 * @return Sky_Seo_Toolkit_Abstract
	 */
	public function setUrl($url)
	{
		$this->_url	= $url;
		return $this;
	}
	
	/**
	 * Get number of back links
	 * 
	 * @return int
	 */
	abstract public function getBackLinksCount();
	
	/**
	 * Get back links
	 * 
	 * @param int $offset
	 * @param int $count
	 * @return array Array of back links
	 */
	abstract public function getBackLinks($offset, $count);

	/**
	 * Get number of indexed pages
	 * 
	 * @return int
	 */
	abstract public function getIndexedPagesCount();
	
	/**
	 * Get list of indexed pages
	 * Each page has following properties:
	 * - title
	 * - description
	 * - url
	 * - displayUrl
	 * - cacheUrl
	 * 
	 * @param int $offset
	 * @param int $count
	 * @return array Array of indexed pages
	 */
	abstract public function getIndexedPages($offset, $count);
}
