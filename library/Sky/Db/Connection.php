<?php
/**
 * TomatoCMS
 * 
 * LICENSE
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE Version 2 
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-2.0.txt
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@tomatocms.com so we can send you a copy immediately.
 * 
 * @copyright	Copyright (c) 2009-2010 TIG Corporation (http://www.tig.vn)
 * @license		http://www.gnu.org/licenses/gpl-2.0.txt GNU GENERAL PUBLIC LICENSE Version 2
 * @version 	$Id: Connection.php 3986 2010-07-25 16:32:46Z huuphuoc $
 * @since		2.0.0
 */

class Sky_Db_Connection
{
        protected $_connection;
        protected $_configs;
        protected $_status = false;


        static protected $_instance;
	/**
	 * @return Tomato_Db_Connection_Abstract
	 */
         
	private function __construct() {
            $configs  = Sky_Config::getConfig()->toArray();
            $this->_configs = $configs['resources'];
	}
        
        
        public function setConfigs(array $configs){
            $this->_configs = $configs;
            return $this;
        }
        
        public function connect(){
            
                if(Zend_Registry::isRegistered('db')) {
                    return  Zend_Registry::get('db');
                }
                
                $options = $this->_configs;
                $db_adapter = $options['db']['adapter'];
                $params = $options['db']['params'];
		
                try {
                    $db = Sky_Db::factory($db_adapter, $params);
                    $db->getConnection();
                    $db->setFetchMode(Zend_Db::FETCH_OBJ);
                    $db->query("SET CHARACTER SET '{$params['charset']}'");
                    
                    Sky_Db_Table_Abstract::setDefaultAdapter($db);
                    
                    
                    $configs = Sky_Config::getConfig();
                    
                    if((bool)$configs->cache->caching) {
                        $cahce = Sky_Cache::getInstance();
                        Sky_Db_Table_Abstract::setDefaultMetadataCache($cahce);
                    }
                    
                    

                    $this->_connection = $db;
                    $this->_status = true;
                    
                    Zend_Registry::set('db', $db);
                    
                    return $db;
                    
                } catch (Zend_Exception $e) {
                    throw new Exception('Não foi possivel estabelecer uma conexão com o banco de dados.');
                }
                
                
        }

                
        /**
	 * @return Zend_Db
	 */
	public static function getInstance()
	{
		if (null == self::$_instance) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}
        
        
        
}
