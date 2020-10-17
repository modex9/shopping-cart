<?php

namespace Entity;
use Traits\Printable;

class Validator
{
    use Printable;

    public const AVAILABALE_CURRENCIES = ["EUR", "USD", "GBP"];

    public const AVAILABLE_DELIMITERS = ",.:;|!@^$*-+";

    public $errors;

    public function __construct()
    {
        $this->eol = $this->getEol();
        $this->errors = [];
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

    public function validateProduct($product)
    {
        $this->errors = [];
        //Check if $product is object and if it is an instance of Product.
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
        $id_length = strlen($product->id);
        if(!is_string($product->id))
        {
            $this->errors[] = "Product ID must be a string.";
        }
        else if($id_length < 3 || $id_length > 6)
        {
            $this->errors[] = "Product ID must be between 3 and 6 symbols.";
        }

        //Product name validation: 2-50 symbol string
        $name_length = strlen($product->name);
        if(!is_string($product->name))
        {
            $this->errors[] = "Product name must be a string.";
        }
        else if($name_length < 2 || $name_length > 50)
        {
            $this->errors[] = "Product name must be between 2 and 50 symbols.";
        }

        //Product price validation: numeric and greater than zero. Validate only when adding to cart.
        if(!is_numeric($product->price) && $product->quantity > 0)
        {
            $this->errors[] = "Product price must be numeric.";
        }
        else if($product->price <= 0 && $product->quantity > 0)
        {
            $this->errors[] = "Product price must be greater than zero.";
        }

        //Product currency validation: simply check if it exists. Matters only when adding product to cart.
        if($product->quantity >= 0)
            self::isAvailableCurrency($product->currency);

        //Product quantity validation: numeric is good. Parse to integer, if numeric.
        if(!is_numeric($product->quantity))
        {
            $this->errors[] = "Product quantity must be a whole number.";
        }
        else
        {
            $product->quantity = (int) $product->quantity;
        }

        if(count($this->errors))
            return false;
        return true;
    }

    public function printErrors()
    {
        foreach ($this->errors as $error)
        {
            $this->printLine($error);
        }
    }
}