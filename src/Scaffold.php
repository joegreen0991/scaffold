<?php

use Illuminate\Database\Connection as Db;

class Scaffold {

    /**
     * Database connection object
     * 
     * @var Illuminate\Database\Connection
     */
    public $db;
    
    /**
     *
     * @var type 
     */
    public $table;
    
    /**
     *
     * @var type 
     */
    protected $elements = array();
    
    /**
     *
     * @var type 
     */
    private $tableColumns = null;
    
    /**
     * 
     * @param \Illuminate\Database\Connection $db
     */
    public function __construct(Db $db, $table)
    {
        $this->db = $db;
        
        $this->table = $table;
    }
    
    /**
     * 
     * @return type
     */
    public function getElements()
    {
        return $this->elements;
    }
    
    /**
     * 
     * @param type $elements
     */
    public function addElements($elements)
    {
        foreach($elements as $name => $element)
        {
            $this->addElement($name, $element);
        }
    }

    /**
     * 
     * @param type $element
     */
    public function addElement($name, $element)
    {        
        $this->elements[$name] = $element;
    }

    public function find(array $values)
    {
        $keys = $this->getPrimaryKey();

        $this->validatePrimaryKey($keys, $values);
                
        foreach(array_values($values) as $i => $value)
        {
            $keys[$i] = array($keys[$i], '=', $value);
        }
        
        $result = $this->search($keys);

        return $result;
    }
    
    public function search(array $searches = array())
    {
        $select = $this->db->table($this->table)
                           ->select($this->prepareColumns());
        
        foreach($searches as $search)
        {
            $select->where($search[0], $search[1], $search[2]);
        }
        
        return $select;
    }
    
    public function insert(array $data)
    {
        $insertId = $this->db->table($this->table)->insertGetId($data);
        
        $keys = array_only($data, $this->getPrimaryKey());
        
        if($autoInc = $this->getAutoIncKey())
        {
            $keys[$autoInc] = $insertId;
        }
        
        return $keys;
    }
    
    public function update(array $data)
    {        
        $primary = $this->getPrimaryKey();
        
        $primaryFields = array_only($data, $primary);
        
        $this->find($primaryFields)
             ->update(array_except($data, $primary));
    }
    
    public function delete(array $data)
    {                
        $primaryFields = array_only($data, $this->getPrimaryKey());
        
        $this->find($primaryFields)
             ->delete();
    }
    
    private function getPrimaryKey()
    {        
        return $this->getTableColumns(function($column){
            return $column['Key'] === 'PRI';
        });
    }
    
    private function getAutoIncKey()
    {
        $filteredColumns = $this->getTableColumns(function($column){
            return array_get($column, 'Extra') === 'auto_increment';
        });
        
        return reset($filteredColumns);
    }
    
    private function getTableColumns($callback = null)
    {
        if($this->tableColumns === null)
        {
            $this->tableColumns = $this->db->select('SHOW COLUMNS FROM ' . $this->table);
        }
        
        $filteredColumns = $callback ? array_filter($this->tableColumns, $callback) : $this->tableColumns;
        
        return array_pluck($filteredColumns, 'Field');
    }
    
    private function prepareColumns()
    {
        $elements = array_filter($this->elements, function($el){
            
            return array_get($el, 'select') !== false;
            
        });
        
        return array_merge($this->getPrimaryKey(), array_keys($elements));
    }
    
    private function validatePrimaryKey(array $keys, array $values)
    {
        $valueCount = count($values);
        $keyCount = count($keys);
        
        if($keyCount !== $valueCount)
        {
            $message = $valueCount > 0 ? "supplied $valueCount values" : "did not supply any values";
            $keyMessage = $keyCount > 1 ? "composed of $keyCount columns: '" . implode("','",$keys) ."'" : "a single column: '$keys[0]'";
            
            throw new Exception('Record cannot be found because you ' . $message . ' for the primary key, which is ' . $keyMessage);
        }
    }
}
