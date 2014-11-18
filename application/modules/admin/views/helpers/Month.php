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
 * @version 	$Id: Allow.php 3971 2010-07-25 10:26:42Z huuphuoc $
 * @since		2.0.0
 */

class Admin_View_Helper_Month extends Zend_View_Helper_Abstract 
{
	public function month($month) 
	{
            $mes = array(1=>'Janeiro',
                     2=>'Fevereiro',
                     3=>'Março',
                     4=>'Abril',
                     5=>'Maio',
                     6=>'Junho',    
                     7=>'Julho',
                     8=>'Agosto',   
                     9=>'Setembro',
                     10=>'Outubro',
                     11=>'Novembro',
                     12=>'Dezembro');
            
		return $mes[(int)$month];
	}	
}
