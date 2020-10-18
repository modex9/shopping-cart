<?php

namespace Entity;
use Traits;

abstract class Entity
{
    use Traits\Printable;

    public function __construct()
    {
        $this->eol = $this->getEol();
    }

}