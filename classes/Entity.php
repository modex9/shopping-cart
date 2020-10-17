<?php

namespace Entity;
use Traits;

abstract class Entity
{
    use Traits\Printable;

    public static $currency_rates =
    [
        'EUR' => 1.0,
        'USD' => 1.0 / 1.14,
        'GBP' => 1.0 / 0.88,
    ];

    public static $default_currency = "EUR";

    public function __construct()
    {
        $this->eol = $this->getEol();
    }

    public function changeDefaultCurrency($currency)
    {
        $validator = new Validator();
        if($validator->isAvailableCurrency($currency))
        {
            $this->printLine("Default currency successfully changed to {$currency}");
            $this->printLineDelimiter();
            self::$default_currency = $currency;
        }
        else
        {
            $validator->printErrors();
        }
    }

}