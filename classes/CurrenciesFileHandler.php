<?php

namespace Entity;

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
        $eol = $this->eol;
        $currency_fields = $this->parseEntityLine($content, false);
        if($currency_fields)
        {
            $currency = $this->createCurrency($currency_fields);
            $validator = new Validator();
            if($validator->validateCurrency($currency))
            {
                $this->printLineDelimiter();
                $this->printLine("{$currency->getName()} was successfully added.");
                parent::write($eol . $content);
                return true;
            }
            else
            {
                $validator->printErrors();
            }
        }
        return false;
    }

    public function createCurrency($product_fields)
    {
        $name = trim($product_fields[0]);
        $rate = trim($product_fields[1]);

        $currency = new Currency($name, $rate);
        return $currency;
    }
}