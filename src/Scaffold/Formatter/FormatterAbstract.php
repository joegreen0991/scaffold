<?php namespace Scaffold\Formatter;

abstract class FormatterAbstract implements FormatterInterface
{
    protected $data;
    protected $elements;
    
    public function __construct(array $elements, array $data)
    {
        $this->data = $data;
        $this->elements = $elements;
    }
    
    public function __toString()
    {
        return $this->render();
    }
}
