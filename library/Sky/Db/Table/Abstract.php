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
Abstract class Sky_Db_Table_Abstract extends Zend_Db_Table_Abstract {

    const DATA_TYPE = "dataType";
    const DATE_FORMAT = "Y-m-d H:i:s";

    //put your code here
    protected $_rowClass = 'Sky_Db_Table_Row';
    protected $_autoDate = true;
    protected $_cache;
    protected $_isCachable = true;
    protected $_dataType;
    protected $_now;
    

    /**
     * Constructor.
     *
     * Supported params for $config are:
     * - db              = user-supplied instance of database connector,
     *                     or key name of registry instance.
     * - name            = table name.
     * - primary         = string or array of primary key(s).
     * - rowClass        = row class name.
     * - rowsetClass     = rowset class name.
     * - referenceMap    = array structure to declare relationship
     *                     to parent tables.
     * - dependentTables = array of child tables.
     * - metadataCache   = cache for information from adapter describeTable().
     *
     * @param  mixed $config Array of user-specified config options, or just the Db Adapter.
     * @return void
     */
    public function __construct($config = array()) {
        $date = new DateTime();
        $this->_now = $date->format(self::DATE_FORMAT);
        
        $this->_cache = Sky_Db_Table_Cache::getInstance();
        
        Sky_Db_Connection::getInstance()->connect();
        
        return parent::__construct($config);
    }

    public function __toString() {
        return $this->_name;
    }

    /**
     * Returns table information.
     *
     * You can elect to return only a part of this information by supplying its key name,
     * otherwise all information is returned as an array.
     *
     * @param  string $key The specific info part to return OPTIONAL
     * @return mixed
     * @throws Zend_Db_Table_Exception
     */
    public function info($key = null) {
        $this->_setupPrimaryKey();

        $info = array(
            parent::SCHEMA => $this->_schema,
            parent::NAME => $this->_name,
            parent::COLS => $this->_getCols(),
            parent::PRIMARY => (array) $this->_primary,
            parent::METADATA => $this->_metadata,
            self::DATA_TYPE => $this->_dataType,
            parent::ROW_CLASS => $this->getRowClass(),
            parent::ROWSET_CLASS => $this->getRowsetClass(),
            parent::REFERENCE_MAP => $this->_referenceMap,
            parent::DEPENDENT_TABLES => $this->_dependentTables,
            parent::SEQUENCE => $this->_sequence
        );

        if ($key === null) {
            return $info;
        }

        if (!array_key_exists($key, $info)) {
            require_once 'Zend/Db/Table/Exception.php';
            throw new Zend_Db_Table_Exception('There is no table information for the key "' . $key . '"');
        }

        return $info[$key];
    }

    /**
     * Inserts a new row.
     *
     * @param  array  $data  Column-value pairs.
     * @return mixed         The primary key of the row inserted.
     */
    public function insert(array $data) {

        $data = $this->_validColumn($data);

        if ((bool) $this->_autoDate) {
            if (in_array('created', $this->_getCols()))
                $data['created'] = $this->_now;

            if (in_array('modified', $this->_getCols()))
                $data['modified'] = $this->_now;
        }

        $this->_clearTags();
        return parent::insert($data);
    }

    /**
     * Updates existing rows.
     *
     * @param  array        $data  Column-value pairs.
     * @param  array|string $where An SQL WHERE clause, or an array of SQL WHERE clauses.
     * @return int          The number of rows updated.
     */
    public function update(array $data, $where) {

        $data = $this->_validColumn($data);

        if ((bool) $this->_autoDate) {
            if (in_array('modified', $this->_getCols()))
                $data['modified'] = $this->_now;
        }
        
        $this->_clearTags();
        return parent::update($data, $where);
    }

    public function delete($where) {
        $this->_clearTags();
        $this->_cache->cleanCacheAll();
        return parent::delete($where);
    }


    protected function _validColumn(array $columns) {
        $cols = $this->_getCols();
        $data = array();

        foreach ($columns as $field => $value) {
            if (in_array($field, $cols)) {
                $data[$field] = $this->_prepareData($field, $value);
            }
        }
        return $data;
    }

    protected function _prepareData($col, $value) {
        $data = array($col => $value);
        if (!isset($this->_dataType))
            return $data[$col];
        if (key_exists($col, $this->_dataType)) {

            switch ($this->_dataType[$col]['type']) {
                case 'datetime':
                    if(empty($data[$col])) {
                        $data[$col] = (key_exists('default', $data[$col]))?$data[$col]['default']:'0000-00-00 00:00:00';
                        break;
                    }
                    $date = str_replace(array('/','.'), '-', $data[$col]);
                    $date = new DateTime($date);
                    $data[$col] = $date->format('Y-m-d H:i:s');
                    break;
                
                case 'now':
                    $date = new DateTime();
                    $data[$col] = $date->format('Y-m-d H:i:s');
                    
                case 'crypt':
                    $data[$col] = Sky_Text_Transform_Crypt::encrypt($data[$col], true);
                    break;

                case 'slug':
                    $data[$col] = Sky_Text_Transform_Slug::makeSlugs($data[$col]);
                    break;
                
                case 'array':
                    $data[$col] = serialize($data[$col]);
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
                    $data[$col] = str_replace(',','.',str_replace('.', '', $data[$col]));
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

    
    protected function _getAllTags($tags = array()){
        $refMap = array();
        if(is_array($this->_referenceMap)){
            foreach ($this->_referenceMap as $row) {
                $refMap[] = $row['refTableClass'];
            } 
        }
        
        $all = array_merge(array($this->_name,'query'),$this->_dependentTables,$refMap,$tags);
        
        return $all;
    }


    protected function _clearTags($tags = array()){
        
        $this->_cache->clean($this->_getAllTags($tags));
    }


    protected function _fetch(Zend_Db_Table_Select $select)
    {
        $cache = $this->_cache;
        $cache->setCachable($this->_isCachable);
        
        if(($data = $cache->load($select))===false) {
            $data = parent::_fetch($select);
            
            $tags = $this->_getAllTags();
            $cache->save($select,$data,$tags);
        } 

        return $data;
    }

}

