<?php
/**
 * @copyright	Copyright (c) 2009-2010 TIG Corporation (http://www.tig.vn)
 * @license		http://www.gnu.org/licenses/gpl-2.0.txt GNU GENERAL PUBLIC LICENSE Version 2
 * @since		2.0.0
 */

class Admin_Services_SessionHandler implements Zend_Session_SaveHandler_Interface 
{
	/**
	 * @var Core_Services_SessionHandler
	 */
	private static $_instance;
	
	/**
	 * Session lifetime
	 * @since 2.0.3
	 * @var int
	 */
	private $_lifetime;
	
	/**
	 * @var Core_Models_Interface_Session
	 */
	private $_sessionDd;
	
	private function __construct()
	{
		$config = Sky_Config::getConfig();
		$this->_lifetime = (isset($config->resources->session->cookie_lifetime))
							? $config->resources->session->cookie_lifetime
							: (int) ini_get('session.gc_maxlifetime');
		
                //$conn = Sky_Db_Connection::getInstance()->connect();
		$this->_sessionDd = Sky_Model_Factory::getInstance()->setModule('admin')->getSession();
                
                
	}
	
	/**
	 * Add this destructor to fix the error: 
	 * "sqlsrv_errors contains an invalid type in ..." when use Sqlsrv
	 * 
	 * @return void
	 */
	public function __destruct()
	{
		session_write_close();
	}
	
	/**
	 * @return Core_Services_SessionHandler
	 */
	public static function getInstance() 
	{
		if (null == self::$_instance) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	public function close() 
	{
		return true;
	}
	
	public function destroy($id) 
	{
		return $this->_sessionDd->delete($id);
	}
	
	public function gc($maxlifetime) 
	{
		$this->_sessionDd->destroy(time());
		return true;
	}
	
	public function open($save_path, $name) 
	{
		return true;	
	}
	
	public function read($id) 
	{
		$return  = '';
		$session = $this->_sessionDd->getById($id);
		
		if ($session != null) {
			$expirationTime = (int) $session->modified + $session->lifetime;
			if ($expirationTime > time()) {
				$return = $session->data;
			} else {
				$this->destroy($id);
			}
		}
		return $return;
	}
	
	public function write($id, $data) 
	{
		$obj = array('session_id' => $id,'data'=> $data,'modified'=>time());
		
		$session = $this->_sessionDd->getById($id);
		if (null == $session) {
			$obj['lifetime'] = $this->_lifetime;
			/**
			 * We could not call:
			 * <code>
			 * $this->_sessionDao->insert(new Core_Models_Session(...))
			 * </code>
			 */
			return $this->_sessionDd->insert($obj);
		} else {
			$obj['lifetime'] = $session->lifetime;
			return $this->_sessionDd->update($obj);
		}
	}
}
