<?php namespace Scaffold\Formatter;

class SearchFormatter extends FormatterAbstract
{
    public static function translateFormArray(array $search)
    {
        $search = array_filter($search, function($search){
            return $search[1];
        });
        
        array_walk($search, function(&$parts){
            if(str_contains($parts[1], 'LIKE'))
            {
                $parts[2] = str_replace('LIKE', $parts[2], $parts[1]);
                $parts[1] = 'LIKE';
            }
        });
        
        return $search;
    }
    
    public function render()
    {
        $search = array('Ignore','='=>'Equal To (=)','<>'=>'Not Equal (<>)','<'=>'Less Than (<)','>'=>'Greater Than (>)','%LIKE%'=>'Contains','LIKE%'=>'Starts With','%LIKE'=>'Ends With');
        
        $searchString = '';
        
        foreach($search as $value => $label)
        {
            $searchString .= '<option value="'.$value.'">'.$label.'</option>';
        }
        
        $string = '<form action="/" method="get">';
        
        foreach($this->elements as $name => $element)
        {
            $string .= '<div><label>'.$element['label'].'</label><input type="hidden" name="'.$name.'[]" value="'.$name.'"/><select name="'.$name.'[]">'.$searchString.'</select><input name="'.$name.'[]"/></div>';
        }
        
        $string .= '<input type="submit"/></form>';
        
        return $string;
    }
}
