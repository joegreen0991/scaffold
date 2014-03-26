<?php namespace Scaffold\Formatter;

use Illuminate\Database\Query\Builder as Builder;

abstract class FormatterAbstract implements FormatterInterface
{
    protected $data;
    
    protected $elements;
    
    protected $primaryKey;
    
    public function __construct(array $elements, Builder $data = null, $primaryKey = null)
    {
        $this->data = $data;

        $this->primaryKey = $primaryKey;
        
        $this->elements = $elements;
    }
    
    public function __toString()
    {
        return $this->render();
    }
}
