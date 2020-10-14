<?php

require_once "Entity.php";

class Product extends Entity
{
    public $id;

    public $name;

    public $quantity = 0;

    public $price = 0.0;

    public $currency = "EUR";

    public function __construct($id = 0, $name = '', $price = 0, $currency = 0, $quantity = 1)
    {
        parent::__construct();
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->currency = $currency;
        $this->quantity = $quantity;
    }

    public function getPrice($currency)
    {
        if($currency == $this->currency)
        {
            return $this->price;
        }
        else
        {
            $product_currency = $this->currency;
            $current_rate = self::$currency_rates[strtoupper($product_currency)];
            $target_rate = self::$currency_rates[strtoupper($currency)];
            return $this->price * ($current_rate / $target_rate);
        }
    }

}