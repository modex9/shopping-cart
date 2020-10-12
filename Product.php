<?php

class Product extends Entity
{
    public $id;

    public $name;

    public $quantity = 0;

    public $price = 0.0;

    public $currency = 1;

    public function __construct($id, $name, $price, $currency = 1, $quantity = 1)
    {
        parent::_cunstruct();
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->currency = $currency;
        $this->quantity = $quantity;
    }
}