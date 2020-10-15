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
    public $eol;

    public function __construct()
    {
        $this->eol = $this->getEol();
    }

    public function changeDefaultCurrency($currency)
    {
        Validator::$errors = [];
        if(Validator::isAvailableCurrency($currency))
        {
            $this->printLine("Default currency successfully changed to {$currency}");
            $this->printLineDelimiter();
            self::$default_currency = $currency;
        }
        else
        {
            foreach (Validator::$errors as $error)
            {
                $this->printLine($error);
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
        $this->printLine(str_repeat($symbol, $n));
    }

    public function printLine($content)
    {
        echo $content . $this->eol;
    }
}