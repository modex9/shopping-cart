<?php

class Validator
{
    public const AVAILABALE_CURRENCIES = ["EUR", "USD", "GBP"];

    public const AVAILABLE_DELIMITERS = ",.:;|!@^$*-+";

    public static $errors;

    public function __construct()
    {
        self::$errors = [];
    }

    public function isAvailableCurrency($currency)
    {
        if(!is_string($currency))
        {
            $this->errors[] = "Currency must be a string.";
            return false;
        }

        $currency = strtoupper($currency);
        if (!in_array($currency, self::AVAILABALE_CURRENCIES))
        {
            $this->errors[] = "Currency {$currency} is not available.";
            return false;
        }
        return true;
    }

    public function printErrors()
    {
        if(count($this->errors))
        {
            foreach ($this->errors as $error)
            {
                echo $error . "\n";
            }
        }
        $this->errors = [];
    }

    public function isFloatOrInt($number)
    {
        return is_float($rate) || is_int($rate);
    }

    public function isCurrencyRate($rate)
    {
        if(!isFloatOrInt($rate))
        {
            $this->errors[] = "Currency rate must be numeric.";
            return false;
        }
        else if($rate < 0)
        {
            $this->errors[] = "Currency rate must be greater than zero.";
            return false;
        }
        return true;
    }

    public function validateProduct($product)
    {
        $this->errors = [];
        //Check if $product is object and if it is an incatnace of Product.
        if(is_object($product) && !($product instanceof Product))
        {
            $this->errors[] = "Object must be an instance of Product," . classname($product) . " object given.";
            return false;
        }
        else if(!is_object($product))
        {
            $this->errors[] = "Object of class Product must be provided.";
            return false;
        }

        //Product ID validation: 3-6 symbol string
        if(!is_string($product->id))
        {
            $this->errors[] = "Product ID must be a string.";
        }
        else if(($id_length = strlen($product->id)) && $id_length >= 3 && $id_length <= 6)
        {
            $this->errors[] = "Product ID must be between 3 and 6 symbols.";
        }

        //Product name validation: 2-50 symbol string
        if(!is_string($product->name))
        {
            $this->errors[] = "Product name must be a string.";
        }
        else if(($name_length = strlen($product->name)) && $name_length >= 2 && $name_length <= 50)
        {
            $this->errors[] = "Product name must be between 2 and 50 symbols.";
        }

        //Product price validation: float or integer and greater than zero
        if(!isFloatOrInt($product->price))
        {
            $this->errors[] = "Product price must be numeric.";
        }
        else if($product->price < 0)
        {
            $this->errors[] = "Product price must be greater than zero.";
        }

        //Product currency validation: simply check if it exists.
        $this->currencyExists($product->currency);

        //Product quantity validation: any integer is good.
        if(!is_int($product->quantity))
        {
            $this->errors[] = "Product quantity must be a whole number.";
        }

        if(count($this->errors))
            return false;
        return true;
    }

    public static function isFileName($filename)
    {
        self::$errors[] = [];
        if(!is_string($filename))
        {
            self::$errors[] = "Filename must be a string.";
            return false;
        }
        else if(($filename_length = strlen($filename)) && $filename_length >= 3 && $filename_length <= 20)
        {
            self::$errors[] = "File name must be between 3 and 20 symbols.";
            return false;
        }
        return true;
    }

    public static function isDelimiter($delimiter)
    {
        self::$errors[] = [];
        if(!is_string($delimiter))
        {
            self::$errors[] = "Delimiter must be a string.";
            return false;
        }
        else if(($delimiter_length = strlen($filename)) && $delimiter_length != 1)
        {
            self::$errors[] = "Delimiter must be a single symbol from this set of symbols: {self::$AVAILABLE_DELIMITERS}";
            return false;
        }
        return true;
    }
}