<?php

namespace Entity;

class Product extends Entity
{
    public $id;

    public $name;

    public $quantity;

    public $price;

    public $currency = "EUR";

    public function __construct($id = 0, $name = '', $price = 0, $currency = 0, $quantity = 1)
    {
        parent::__construct();
        $this->id = $id;
        $this->name = $name;
        $this->quantity = $quantity;
        $this->price = $price;
        $this->currency = $currency;
    }

    public function getPrice($currency)
    {
        $currency = strtoupper($currency);
        $product_currency = strtoupper($this->currency);
        $available_currencies = Currency::getAvailableCurrencies();
        $validator = new Validator();
        if($currency == $this->currency)
        {
            return $this->price;
        }
        else if($validator->isAvailableCurrency($product_currency) && $validator->isAvailableCurrency($currency))
        {
            $current_rate = $available_currencies[$product_currency];
            $target_rate = $available_currencies[$currency];
            return $this->price * ($current_rate / $target_rate);
        }
    }

}