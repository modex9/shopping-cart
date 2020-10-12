<?php

require_once "Validator.php";

abstract class Entity
{
    public static $default_currency = "EUR";

    public $validator;

    public function __construct()
    {
        $this->validator = new Validator();
    }

    public function changeDefaultCurrency($currency)
    {
        if($this->validator->isAvailableCurrnecy($currency))
        {

        }
    }
}