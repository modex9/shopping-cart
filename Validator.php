<?php

class Validator
{
    public const AVAILABALE_CURRENCIES = ["EUR", "USD", "GBP"];

    public $errors;

    public function __construct()
    {
        $this->errors = [];
    }

    public function isAvailableCurrnecy($currency)
    {
        
    }

}