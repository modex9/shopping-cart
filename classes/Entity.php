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

    public static $default_currency = "gbp";

    //End of line, depending on programs environment.
    public static $eol;

    public $validator;

    public function __construct()
    {
        self::$eol = $this->getEol();
        $this->validator = new Validator();
    }

    public function changeDefaultCurrency($currency)
    {
        if($this->validator->isAvailableCurrency($currency))
        {
            self::$default_currency = $currency;
        }
    }

    private function getEol()
    {
        if (PHP_SAPI == 'cli') 
        { 
            return PHP_EOL;
        } 
        else
        {
            return "<br>";
        }
    }

    public function printLineDelimiter($symbol = '-', $n = 40)
    {
        echo str_repeat($symbol, $n).self::$eol;
    }
}