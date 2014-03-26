<?php namespace Scaffold\Formatter;

class FormFormatter extends FormatterAbstract
{
    public function render()
    {
        $string = '<form method="post">';
        
        $fieldset = new \Fieldset\Fieldset();
        
        foreach($this->elements as $name => $element)
        {
            $element['name'] = $name;

            $field = new \Fieldset\InputElement(array_get($element, 'type', 'text'), $element);

            $fieldset->addTag($field);
        }
        
        $fieldset->populate($this->data->first());
        
        $fieldset->addTag(new \Fieldset\InputElement('submit'));
        
        $string .= $fieldset->render();
        
        $string .= '</form>';
        
        return $string;
    }
}
