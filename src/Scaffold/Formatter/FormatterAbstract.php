<?php namespace Scaffold\Formatter;

use Illuminate\Database\Query\Builder as Builder;

abstract class FormatterAbstract implements FormatterInterface
{
    protected $data;
    protected $elements;
    
    public function __construct(array $elements, Builder $data)
    {
        $this->data = $data;
        $this->elements = $elements;
    }
    
    public function __toString()
    {
        return $this->render();
    }
}
