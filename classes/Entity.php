<?php

namespace Entity;
use Traits;

abstract class Entity
{
    use Traits\Printable;

    public static $currency_rates =
    [
        'EUR' => 1.0,
        'USD' => 1.0 / 1.14,
        'GBP' => 1.0 / 0.88,
    ];
    public function __construct()
    {
        $this->eol = $this->getEol();
    }

}