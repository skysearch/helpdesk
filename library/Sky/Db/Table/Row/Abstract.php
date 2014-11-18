<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Abstract
 *
 * @author Renato David
 */
Abstract class Sky_Db_Table_Row_Abstract extends Zend_Db_Table_Row_Abstract {
    
    protected $_dataType;
    
    
    /**
     * Constructor.
     *
     * Supported params for $config are:-
     * - table       = class name or object of type Zend_Db_Table_Abstract
     * - data        = values of columns in this row.
     *
     * @param  array $config OPTIONAL Array of user-specified config options.
     * @return void
     * @throws Zend_Db_Table_Row_Exception
     */
    public function __construct(array $config = array()) {
        parent::__construct($config);
        $info = $this->_getTable()->info();
        $this->_dataType = $info['dataType'];
        
    }
    
    /**
     * Retrieve row field value
     *
     * @param  string $columnName The user-specified column name.
     * @return string             The corresponding column value.
     * @throws Zend_Db_Table_Row_Exception if the $columnName is not a column in the row.
     */
    public function __get($columnName)
    {
        $columnName = $this->_transformColumn($columnName);
        if (!array_key_exists($columnName, $this->_data)) {
            require_once 'Zend/Db/Table/Row/Exception.php';
            throw new Zend_Db_Table_Row_Exception("Specified column \"$columnName\" is not in the row");
        }
        
        return $this->_prepareData($columnName);
    }
    
    /**
     * Set row field value
     *
     * @param  string $columnName The column key.
     * @param  mixed  $value      The value for the property.
     * @return void
     * @throws Zend_Db_Table_Row_Exception
     */
    public function __set($columnName, $value)
    {
        $columnName = $this->_transformColumn($columnName);
        if (!array_key_exists($columnName, $this->_data)) {
            require_once 'Zend/Db/Table/Row/Exception.php';
            throw new Zend_Db_Table_Row_Exception("Specified column \"$columnName\" is not in the row");
        }

        if($value instanceof DateTime) {
            $value = $value->format('Y-m-d H:i:s');
        } else if(is_bool($value)) {
            $value = (int)$value;
        } 
        
        $this->_data[$columnName] = $value;
        $this->_modifiedFields[$columnName] = true;
    }
    

    /**
     * Allows post-insert logic to be applied to row.
     * Subclasses may override this method.
     *
     * @return void
     */
    protected function _postInsert()
    {
        Sky_Db_Table_Cache::getInstance()->clean($this->_tableClass);
    }

    /**
     * Allows post-update logic to be applied to row.
     * Subclasses may override this method.
     *
     * @return void
     */
    protected function _postUpdate()
    {
        Sky_Db_Table_Cache::getInstance()->clean($this->_tableClass);
    }

    /**
     * Allows post-delete logic to be applied to row.
     * Subclasses may override this method.
     *
     * @return void
     */
    protected function _postDelete()
    {
        Sky_Db_Table_Cache::getInstance()->clean($this->_tableClass);
    }
    
    /*protected function _refresh(){
        
        //$tags = array_merge(array($this->_name),$this->_dependentTables);
        //Zend_Debug::dump($this->_tableClass);exit;
        
        Sky_Db_Table_Cache::getInstance()->clean($this->_tableClass);

        parent::_refresh();
    }*/
    
    protected function _prepareData($col) {
        $data = $this->_data;
        if(!isset($this->_dataType)) return $data[$col];
        if(key_exists($col, $this->_dataType)) {
            
            switch ($this->_dataType[$col]['type']){
                case 'datetime':
                    if((int)$data[$col]>0) {
                        $data[$col] = new DateTime($data[$col]);
                        break;
                    }
                    $data[$col] = null;
                    break;
                    
                case 'now':
                    $data[$col] = new DateTime();
                    break;
                    
                case 'crypt':
                    $data[$col] = Sky_Text_Transform_Crypt::decrypt($data[$col],true);
                    break;
                
                case 'slug':
                    $data[$col] = $data[$col];
                    break;
                
                case 'array':
                    $data[$col] = unserialize($data[$col]);
                    break;
                
                case 'int':
                case 'integer':    
                case 'float':
                case 'string':
                case 'bool':   
                     $function = $this->_dataType[$col]['type'];
                     $value = $data[$col];
                     eval("\$data[\"$col\"] = ($function)\"$value\";");
                     break;
                
                case 'currency':
                     $data[$col] = number_format($data[$col],2,',','.');
                     break;
                 
                default:
                     $function = $this->_dataType[$col]['type'];
                     $value = $data[$col];
                     $value = str_replace('$', '\$', $value);
                     eval("\$data[\"$col\"] = $function(\"$value\");");
                     break;
            }
        }
        return $data[$col];
    }

    /**
     * Returns the column/value data as an array.
     *
     * @return array
     */
    public function toArray()
    {
        $data = array();
        
        foreach ($this->_data as $key => $value) {
            $data[$key] = $this->_prepareData($key);
        }
        return (array)$data;
    }
}

