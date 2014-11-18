<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Sky_Doctrine_Controller_Action_Helper_Entity extends Zend_Controller_Action_Helper_Abstract
{

	/**
	* Entity manager
	* @var \Doctrine\ORM\EntityManager
	*/
	private $_em = null;
	
	/**
	* Return entity manager
	* Could be overriden to support custom entity manager
	*
	* @return \Doctrine\ORM\EntityManager
	*/
	public function getEntityManager() 
	{
	    if ( $this->_em == null) {
    		$this->_em = Zend_Registry::get('em');
	    }
    	    return $this->_em;
	}
        
        
        /**
	* Set entity manager
	* @param \Doctrine\ORM\EntityManager
	* @return true
	*/
        public function setEntityManager($em) 
	{
	    if ( $this->_em == null) {
                Zend_Registry::set('em',$em);
    		$this->_em = $em;
	    }
            
            if(Zend_Registry::isRegistered('em')) {
                return true;
            }
	}

	
	/**
	* @return \Doctrine\ORM\EntityManager
	*/
	public function direct() 
	{
	    return $this->getEntityManager();
	}
	
}

