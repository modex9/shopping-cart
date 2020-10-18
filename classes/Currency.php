<?php 

namespace Entity;

class Currency extends Entity
{
    private $name;

    private $rate;

    public function __construct($name = "EUR", $rate = 1.0)
    {
        parent::__construct();
        $this->name = $name;
        $this->rate = $rate;
    }

    public function getRate()
    {
        return $this->rate;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setRate($rate)
    {
        $this->rate = $rate;
    }

}