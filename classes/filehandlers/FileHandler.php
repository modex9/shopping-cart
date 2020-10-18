<?php 

namespace Entity\FileHandlers;
use Traits;

abstract class FileHandler
{
    use Traits\Printable;

    public $filename = "input.txt";

    public $columns_delimiter = ';';

    protected $entity;

    protected $num_columns;

    public function __construct()
    {
        $this->eol = $this->getEol();
        $this->entity = static::ENTITY;
        $this->num_columns = static::NUM_COLUMNS;
    }

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

    protected function write($content)
    {
        if(!file_exists($this->filename) || !file_get_contents($this->filename))
            file_put_contents($this->filename, $content, FILE_APPEND | LOCK_EX);
        else    
            file_put_contents($this->filename, PHP_EOL . $content, FILE_APPEND | LOCK_EX);
    }

    protected function getLines()
    {
        $file_contents = $this->read();
        if(!$file_contents)
            return false;
        $lines = explode("\n", $file_contents);
        return $lines;
    }

    public function parseEntityLine($line, $file = true)
    {
        $entity_fields = explode($this->columns_delimiter, $line);
        if(count($entity_fields) != $this->num_columns)
        {
            if($file)
                $this->printLine("Failed reading {$this->entity} information at line {$line}. Check file formatting.");
            else
                $this->printLine("Failed to add {$this->entity}. Check your input formatting.");
            return false;
        }
        return $entity_fields;
    }
}