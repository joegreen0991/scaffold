<?php namespace Scaffold\Formatter;

use Scaffold\ScaffoldDataSet;

abstract class FormatterAbstract implements FormatterInterface
{
    protected $data;
    
    protected $elements;
    
    public function __construct(array $elements, ScaffoldDataSet $data = null)
    {
        $this->elements = $elements;
        
        $this->data = $data;
    }
    
    public function __toString()
    {
        return $this->render();
    }
}
