<?php

namespace Entity;

class CurrenciesContainer extends Entity
{
    //Array of Currency objects.
    private $currencies;

    private static $instance;

    public function __construct($currencies = [])
    {
        parent::__construct();
        foreach ($currencies as $currency)
        {
            $this->currencies[$currency->getName()] = $currency;
        }
    }

    public static function getInstance($currencies = [])
    {
        if(!isset(self::$instance))
        {
            self::$instance = new CurrenciesContainer($currencies);
            return self::$instance;
        }
        else
        {
            return self::$instance;
        }
    }

    public function getAvailableCurrencies()
    {
        $currencies = $this->currencies;
        if($currencies && !empty($currencies))
        {
            return $this->currencies;
        }
        else
        {
            $this->printLine("No currencies were found");
            return false;
        }
        
    }

    public function printAvailableCurrencies()
    {
        $currencies = $this->currencies;
        if($currencies && !empty($currencies))
        {
            $this->printLine("Currency | Rate");
            foreach($currencies as $currency)
            {
                $this->printLine($currency->getName() . " | " . $currency->getRate());
            }
        }
        else
        {
            $this->printLine("No currencies were found");
        }
    }

    public function getCurrency($currency_name)
    {
        $available_currencies = $this->getAvailableCurrencies();
        return isset($available_currencies[$currency_name]) ? $available_currencies[$currency_name] : false;
    }

    public function containsCurrency($currency_name)
    {
        return isset($this->currencies[$currency_name]);
    }

    public function addCurrency($currency)
    {
        $this->currencies[$currency->getName()] = $currency;
    }

    public function updateCurrency($updated_currency)
    {
        $currency_name = $updated_currency->getName();
        if($currency = $this->getCurrency($currency_name))
        {
            $this->currencies[$currency_name]->setRate($updated_currency->getRate());
        }
        else
        {
            $this->printLineDelimiter();
            $this->printLine("Could not update currency {$currency_name}. Currency not found");
        }
    }
}