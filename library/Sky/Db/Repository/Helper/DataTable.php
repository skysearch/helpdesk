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
class Sky_Db_Repository_Helper_DataTable {
    
    
    protected $_columns;
    
    
    /*
     * Set Columns 
     * format from array
     * array(
     *          'comun_name_1'=>array('type'=>'date','format'=>'d/m/Y'),
     *          'comun_name_2'=>array('type'=>'currency')
     *          'comun_name_3'=>array('type'=>'switch','cases'=>array('1'=>'ativo','0'='inativo')))
     *      ),
     *      
     * @param array $comuns
     */
    public function setColumns(array $columns) {
        $this->_columns = $columns;
    }
    
    

    /*
     * Return columns  array
     * @return array $_columns
     */
    public function getColumns() {
       return $this->_columns;
    }
    
    /*
     * Return column array
     * @param string $column
     * @return array $_columns[$column]
     */
    public function getColumn($column) {
       return $this->_columns[$column];
    }
    
    
    /*
     * Return columns names in array
     * @return array $cols
     */
    public function getColumnsName() {
        $cols = array();
        
        foreach ($this->_columns as $col=>$value) {
            $cols[] = $col;
        }
        
        return $cols;
    }

    protected function _prepareData($col,$value) {
        if(count($this->_columns)<=0) 
            return null;
        
        if(key_exists($col, $this->_columns) && 
                key_exists('type', $this->_columns[$col]) &&
               !in_array('ignore', $this->_columns[$col])) {
            
            //$value = trim($value);
            
            switch ($this->_columns[$col]['type']){
                case 'datetime':
                    $value = ($value instanceof DateTime)?$value:new DateTime($value); 
                    $value = $value->format($this->_columns[$col]['format']);
                    break;
                
                case 'now':
                    $value = new DateTime();
                    $value = $value->format('Y-m-d H:i:s');
                    break;
                
                case 'int':
                case 'string':
                case 'bool':   
                     $function = $this->_columns[$col]['type'];
                     eval("\$value = ($function)\"$value\";");
                     break;
                 
                 case 'currency':
                    $value = $value;
                    break;
                
                case 'switch':
                    //if(is_null($value) || empty($value)) 
                       //return $value;
                    
                    if($this->_columns[$col]['cases']['type']=='bool'){
                        $args = $this->_columns[$col]['cases']['args'];
                        return ((bool)$value)?$args[0]:$args[1];
                    }
                    
                    if(!key_exists($value, $this->_columns[$col]['cases']))
                            return null;
                    
                    //print("value[$value]");
                    $value = $args[$value];
                    
                    break;
                 
                default:
                     $function = $this->_columns[$col]['type'];
                     eval("\$value = $function(\"$value\");");
                     break;
            }
        }
        return $value;
    }
    
    public function getJsonDataTable(Sky_Db_Table_Abstract $model ,Zend_Db_Select $select, array $post, $key, $action = null){
        
        
        /*$alnum = new Zend_Filter_Alnum();
        $int = new Zend_Filter_Int();
        $digits = new Zend_Filter_Digits();
        $striptag = new Zend_Filter_StripTags();
       */
        
        if(count($this->_columns)<=0) 
            return null;
        
       
        
        $columns = $this->getColumnsName();
        
        /*
	 * Ordering
	 */
	if (isset($post['iSortCol_0']))
	{
		for ( $i=0;$i<intval($post['iSortingCols']);$i++ )
		{
			if ($post['bSortable_'.intval($post['iSortCol_'.$i])]=="true")
				$select->order($columns[(int)$post['iSortCol_'.$i]],$post['sSortDir_'.$i]);

		}
	} else {
		$select->order($key . ' DESC');
	}
        
        
        $adapter = $select->getAdapter();
        $sql = null;
        /* 
	 * Filtering
	 * NOTE this does not match the built-in DataTables filtering which does it
	 * word by word on any field. It's possible to do here, but concerned about efficiency
	 * on very large tables, and MySQL's regex functionality is very limited
	 */
	if (!empty($post['sSearch']))
	{
		for ( $i=0 ; $i<count($columns) ; $i++ ){
                    $column = $this->getColumn($columns[$i]);
                    if(in_array('no_search', $column))
                            continue;
                    
                    $col = (key_exists('real_name',$column))?$column['real_name']:$columns[$i];
			$sql .= $adapter->quoteInto("{$col} LIKE (?) OR ","%{$post['sSearch']}%");
                        //$select->orWhere($col." LIKE (?) ","%{$post['sSearch']}%");
                }
                if(!is_null($sql)){
                    $filter = substr($sql,0,-3);
                    $select->where("{$filter}");
                    unset($sql);
                }
	}
        
        $sql = null;
        /* Individual column filtering */
        for ( $i=0 ; $i<count($columns) ; $i++ )
	{
		if ($post['bSearchable_'.$i] == "true" && !empty($post['sSearch_'.$i]))
                        $sql .= $adapter->quoteInto("{$columns[$i]} LIKE (?) OR ","%{$post['sSearch_'.$i]}%");
                        //$select->orWhere($columns[$i]." LIKE (?) ","%{$post['sSearch_'.$i]}%");
	}
        if(!is_null($sql)) {
            $filter = substr($sql,0,-3);
            $select->where("{$filter}");
            unset($sql);
        }
        
         /* 
	 * Paging
	 */
	if (isset($post['iDisplayStart']) && $post['iDisplayLength'] != '-1') {
		$select->limit($post['iDisplayLength'],$post['iDisplayStart']);
        }
        
        
        $result = $model->fetchAll($select);
        
        $iFilteredTotal = count($model->fetchAll());
        $iTotal = count($result);
        
        
        
        /*
	 * Output
	 */
	$sOutput = '{';
	$sOutput .= '"sEcho": '.intval($post['sEcho']).', ';
	$sOutput .= '"iTotalRecords": '.$iTotal.', ';
	$sOutput .= '"iTotalDisplayRecords": '.$iFilteredTotal.', ';
	$sOutput .= '"aaData": [ ';
        $actions = unserialize($post['actions']);
        $element = $post['element'];
        
        foreach($result as $row){
            
            $sOutput .= "[";
		for ( $i=0 ; $i<count($columns) ; $i++ ) {
			if(in_array('noRender',$this->_columns[$columns[$i]])) 
                                continue;
                        
                    	$sOutput .= '"<div>'.str_replace('"', '\"',$this->_prepareData($columns[$i], $row[$columns[$i]])).'</div>",';
		}
                
		/*
		 * Optional Configuration:
		 * If you need to add any extra columns (add/edit/delete etc) to the table, that aren't in the
		 * database - you can do it here
		 */
                
                /*if(!is_null($action)){
                    if(in_array('edit', $action)){
                        $sOutput .= '"<a class=\"button-edt\" href=\"javascript:void(0)\" id=\"edt'.$row[$key].'\" onclick=\"$.editar('.$row[$key].')\"><span>Editar</span></a>",';
                    }
                    if(in_array('del', $action)){
                        $sOutput .= '"<a class=\"button-del\" href=\"javascript:void(0)\" id=\"del'.$row[$key].'\" onclick=\"$.del('.$row[$key].')\"><span>Apagar</span></a>",';
                    }
                }*/
                
                foreach ($actions as $k=>$v){
                    $sOutput .= '"<a class=\"button-'.$k.'\" href=\"javascript:void(0)\" id=\"'.$k.''.$row[$key].'\" onclick=\"$.'.$element.'_'.$k.'('.$row[$key].')\"><span>'.$v.'</span></a>",';
                }
                
		$sOutput = substr_replace( $sOutput, "", -1 );
		$sOutput .= "],";
        }
        
        
        $sOutput = substr_replace( $sOutput, "", -1 );
	$sOutput .= ']}';
	
	return $sOutput;
    }
}

