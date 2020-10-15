<?php 

require_once "Entity.php";

class FileHandler extends Entity
{
    public $filename = "input.txt";

    public function read()
    {
        if(file_exists($this->filename))
            return file_get_contents($this->filename);
        else
        {
            $this->write('');
            return false;
        }
            
    }

    public function write($content)
    {
        file_put_contents($this->filename, $content, FILE_APPEND | LOCK_EX);
    }
}