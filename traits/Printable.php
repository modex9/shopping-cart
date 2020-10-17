<?php

namespace Traits;

trait Printable
{
    //End of line, depending on program's environment.
    public $eol;

    private function getEol()
    {
        if (PHP_SAPI == 'cli') 
        { 
            return PHP_EOL;
        } 
        else
        {
            return "<br>";
        }
    }

    public function printLineDelimiter($symbol = '-', $n = 40)
    {
        $this->printLine(str_repeat($symbol, $n));
    }

    public function printLine($content)
    {
        echo $content . $this->eol;
    }
}