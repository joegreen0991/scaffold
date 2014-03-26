<?php namespace Scaffold\Formatter;

class JsonFormatter extends FormatterAbstract
{
    public function render()
    {
        return json_encode($this->data);
    }
}
