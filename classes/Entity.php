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

    //End of line, depending on programs environment.
    public static $eol;

    public function __construct()
    {
        self::$eol = $this->getEol();
    }

    public function changeDefaultCurrency($currency)
    {
        Validator::$errors = [];
        $eol = self::$eol;
        if(Validator::isAvailableCurrency($currency))
        {
            echo "Default currency successfully changed to {$currency}".$eol;
            $this->printLineDelimiter();
            self::$default_currency = $currency;
        }
        else
        {
            foreach (Validator::$errors as $error)
            {
                echo $error . $eol;
            }
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