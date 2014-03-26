<?php namespace Scaffold;

use \Illuminate\Database\Query\Builder as Builder;

class ScaffoldDataSet {

    protected $query;

    protected $table;

    protected $primaryKey;

    /**
     * 
     * @param \Illuminate\Database\Query\Builder $data
     */
    public function __construct(Builder $query, $table, array $primaryKey)
    {
        $this->query = $query;
        
        $this->table = $table;
        
        $this->primaryKey = $primaryKey;
    }
    
    /**
     * 
     * @return type
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }
    
    /**
     * 
     * @return type
     */
    public function getTable()
    {
        return $this->table;
    }
    
    /**
     * 
     * @return type
     */
    public function getQuery()
    {
        return $this->query;
    }
    
    /**
     * 
     * @param type $name
     * @param type $arguments
     * @return type
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array(array($this->query, $name), $arguments);
    }
}
