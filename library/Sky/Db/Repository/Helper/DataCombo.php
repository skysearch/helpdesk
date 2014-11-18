<?php

/*
 * n - node
 * o - object
 * a - array
 * s - string
 * b - boolean
 * f - float
 * i - integer
 * fn - function
 */


/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Sky_Db_Repository_Helper_DataCombo {
    
    public function getDataCombo($primary,$value,$model,$select=null,$empty=true){
        
        $sOutput = array();
        if($empty){
           $sOutput = array(''=>'--'); 
        }
        if($model instanceof Sky_Db_Table_Abstract ){
            $rows = $model->fetchAll($select);
        } else if($model instanceof Zend_Db_Table_Rowset) {
            $rows = $model->toArray();
        }
        
        foreach ($rows as $v){
            $sOutput[$v[$primary]] = strip_tags($v[$value]);
        }
        
	return $sOutput;
    }
    
    
}

