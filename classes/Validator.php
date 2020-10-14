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

    public static function isAvailableCurrency($currency)
    {
        if(!is_string($currency))
        {
            self::$errors[] = "Currency must be a string.";
            return false;
        }

        $currency = strtoupper($currency);
        if (!in_array($currency, self::AVAILABALE_CURRENCIES))
        {
            self::$errors[] = "Currency {$currency} is not available.";
            return false;
        }
        return true;
    }

    public static function validateProduct($product)
    {
        self::$errors = [];
        //Check if $product is object and if it is an instance of Product.
        if(is_object($product) && !($product instanceof Product))
        {
            self::$errors[] = "Object must be an instance of Product," . classname($product) . " object given.";
            return false;
        }
        else if(!is_object($product))
        {
            self::$errors[] = "Object of class Product must be provided.";
            return false;
        }

        //Product ID validation: 3-6 symbol string
        $id_length = strlen($product->id);
        if(!is_string($product->id))
        {
            self::$errors[] = "Product ID must be a string.";
        }
        else if($id_length < 3 || $id_length > 6)
        {
            self::$errors[] = "Product ID must be between 3 and 6 symbols.";
        }

        //Product name validation: 2-50 symbol string
        $name_length = strlen($product->name);
        if(!is_string($product->name))
        {
            self::$errors[] = "Product name must be a string.";
        }
        else if($name_length < 2 || $name_length > 50)
        {
            self::$errors[] = "Product name must be between 2 and 50 symbols.";
        }

        //Product price validation: numeric and greater than zero. Validate only when adding to cart.
        if(!is_numeric($product->price) && $product->quantity > 0)
        {
            self::$errors[] = "Product price must be numeric.";
        }
        else if($product->price <= 0 && $product->quantity > 0)
        {
            self::$errors[] = "Product price must be greater than zero.";
        }

        //Product currency validation: simply check if it exists. Matters only hen adding product to cart.
        if($product->quantity > 0)
            self::isAvailableCurrency($product->currency);

        //Product quantity validation: any integer is good.
        if(!is_numeric($product->quantity))
        {
            self::$errors[] = "Product quantity must be a whole number.";
        }

        if(count(self::$errors))
            return false;
        return true;
    }

}