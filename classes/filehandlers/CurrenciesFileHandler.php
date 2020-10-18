<?php

namespace Entity\FileHandlers;
use Entity\FileHandlers\FileHandler;
use Entity\Validator;
use Entity\Currency;
use Entity\CurrenciesContainer; 

class CurrenciesFileHandler extends FileHandler
{
    protected const NUM_COLUMNS = 2;

    protected const ENTITY = 'currency';

    public function __construct($filename = 'currencies.txt', $delimiter = ' ')
    {
        parent::__construct();
        $this->filename = $filename;
        $this->columns_delimiter = $delimiter;
    }

    public function getCurrencies()
    {
        $currency_lines = $this->getLines();
        if(!$currency_lines || (is_array($currency_lines) && count($currency_lines) == 0))
            return false;

        $currencies = [];
        $validator = new Validator();
        foreach($currency_lines as $i => $currency)
        {
            $currency_fields = $this->parseEntityLine($currency);
            if(!$currency_fields)
                continue;

            $currency_obj = $this->createCurrency($currency_fields);
            if($validator->validateCurrency($currency_obj))
                $currencies[] = $currency_obj;
        }

        return $currencies;
    }

    public function write($content)
    {
        $currency_fields = $this->parseEntityLine($content, false);
        if($currency_fields)
        {
            $currency = $this->createCurrency($currency_fields);
            $validator = new Validator();
            if($validator->validateCurrency($currency))
            {
                $currencies_container = CurrenciesContainer::getInstance();
                $this->printLineDelimiter();
                if($currencies_container->containsCurrency($currency->getName()))
                {
                    if($this->updateCurrency($currency))
                    {
                        $this->printLine("{$currency->getName()} was successfully updated.");
                        $currencies_container->updateCurrency($currency);
                    }
                    else
                    {
                        $this->printLine("Failed to update {$currency->getName()} currency.");
                    }
                }
                else
                {
                    $currencies_container->addCurrency($currency);
                    $this->printLine("{$currency->getName()} was successfully added.");
                    parent::write($content);
                }
                return true;
            }
            else
            {
                $validator->printErrors();
            }
        }
        return false;
    }

    public function createCurrency($currency_fields)
    {
        $name = trim($currency_fields[0]);
        $rate = trim($currency_fields[1]);

        $currency = new Currency($name, $rate);
        return $currency;
    }

    public function updateCurrency($updated_currency)
    {
        $currency_lines = $this->getLines();
        if(!$currency_lines || (is_array($currency_lines) && count($currency_lines) == 0))
            return false;

        foreach($currency_lines as $i => $currency)
        {
            $currency_fields = $this->parseEntityLine($currency);
            if(!$currency_fields)
                continue;
            if($currency_fields[0] == $updated_currency->getName())
            {
                $currency_fields[1] = $updated_currency->getRate();
                $currency_lines[$i] = implode(" ", $currency_fields);
                file_put_contents($this->filename, implode(PHP_EOL, $currency_lines));
                return true;
            }
        }
        return false;
    }
}