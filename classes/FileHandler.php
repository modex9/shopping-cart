<?php 


class FileHandler
{
    public $filename = "../input.txt";

    public function __construct($filename = null)
    {
        if($filename && Validator::isFileName($filename))
        {
            $this->filename = $filename;
        }
    }

    public function read()
    {
        return file_get_contents($this->filename);
    }

    public function write($content)
    {
        file_put_contents($this->filename, $content, FILE_APPEND | LOCK_EX);
    }
}