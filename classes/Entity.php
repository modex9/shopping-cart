<?php

require_once "Validator.php";

abstract class Entity
{
    public static $currency_rates =
    [
        'EUR' => 1.0,
        'USD' => 1.0 / 1.14,
        'GBP' => 1.0 / 0.88,
    ];

    public static $default_currency = "EUR";

    public $validator;

    public function __construct()
    {
        $this->validator = new Validator();
    }

    public function changeDefaultCurrency($currency)
    {
        if($this->validator->isAvailableCurrency($currency))
        {
            self::$default_currency = $currency;
        }
    }
}