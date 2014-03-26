<?php namespace Scaffold\Formatter;

class ExportFormatter extends FormatterAbstract
{
    public function render()
    {
        $this->sendHeaders();
        
        $outstream = fopen('php://output', 'w');

        fputcsv($outstream, array_keys($this->elements));
        
        foreach ($this->data as $row) 
        {
            fputcsv($outstream, $row);
        }
        
        fclose($outstream);
        
        return '';
    }
    
    private function sendHeaders()
    {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=export.csv');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

    }
}
