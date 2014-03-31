<?php namespace Scaffold\Formatter;

use Scaffold\ScaffoldDataSet;

abstract class FormatterAbstract implements FormatterInterface
{
    protected $data;
    
    protected $elements;
    
    protected $filterEquals = array();
    
    protected $filterNotEquals = array();
    
    public function __construct(array $elements, ScaffoldDataSet $data = null)
    {
        $this->elements = $elements;
        
        $this->data = $data;
    }
    
    protected function filterElements()
    {
        return array_filter($this->elements, function($element){
            
            foreach($this->filterNotEquals as $column => $filter)
            {
                if(array_get($element, $column) !== $filter)
                {
                    return true;
                }
            }
            
            foreach($this->filterEquals as $column => $filter)
            {
                if(array_get($element, $column) === $filter)
                {
                    return true;
                }
            }
        });
    }
    
    public function __toString()
    {
        return $this->render();
    }
}
