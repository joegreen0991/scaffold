<?php namespace Scaffold\Formatter;

class TableFormatter extends FormatterAbstract
{
    public function render()
    {
        $string = '<table>';
        
        foreach($this->data as $row)
        {
            $string .= '<tr>';
            
            foreach($this->elements as $name => $element)
            {
                $string .= '<td>'.$row[$name].'</td>';
            }
        
            $string .= '</tr>';
            
        }
        $string .= '</table>';
        
        return $string;
    }
}
