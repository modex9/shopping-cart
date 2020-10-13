<?php

class Product extends Entity
{
    public $id;

    public $name;

    public $quantity = 0;

    public $price = 0.0;

    public $currency = "EUR";

    public function __construct($id = 0, $name = '', $price = 0, $currency = 0, $quantity = 1)
    {
        parent::_cunstruct();
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->currency = $currency;
        $this->quantity = $quantity;
    }

    public function getPrice()
    {
        $default_currency_rate = self::$currency_rates[self::$default_currency];
        $cart_currency_rate = self::$currency_rates[$product->currency] / $default_currency_rate;
        if($this->validator->isCurrencyRate($cart_currency_rate))
            return $this->price * $cart_currency_rate;
        else
            $this->validator->printErrors();
    }

}