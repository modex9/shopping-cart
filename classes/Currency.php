<?php 

namespace Entity;

class Currency extends Entity
{
    private $name;

    private $rate;

    private static $available_currencies = [];

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

    public static function setAvailableCurrencies($currencies)
    {
        foreach($currencies as $currency)
        {
            self::$available_currencies[$currency->name] = $currency->rate;
        }
    }

    public static function getAvailableCurrencies()
    {
        return self::$available_currencies;
    }

}