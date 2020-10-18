<?php 

namespace Entity;

class Cart extends Entity
{
    public $products;

    public $total = 0;

    public static $default_currency = "EUR";
    
    public function __construct($products)
    {
        parent::__construct();
        if($products)
        {
            foreach ($products as $product)
            {
                if($product->quantity > 0)
                {
                    $this->addToCart($product);
                }
                else
                {
                    $this->removeFromCart($product);
                }
            }
        }
        else
        {
            $this->products = [];
        }
    }

    public function getTotal()
    {
        $this->total = 0;
        $validator = new Validator();

        $default_currency = self::$default_currency;
        if($validator->isAvailableCurrency($default_currency))
        {
            foreach ($this->products as $product)
            {
                $this->total += $product->quantity * $product->getPrice($default_currency);
            }
            $this->total = round($this->total, 2);
            return true;
        }
        else
        {
            $this->printLineDelimiter();
            $this->printLine("Cart currency is set to {$default_currency}, but it's not available. Please change currency or add a new one.");
            return false;
        }
    }

    public function printCartTotal()
    {
        if($this->getTotal())
        {
            $currency = self::$default_currency;
            $this->printLineDelimiter();
            $this->printLine("Cart total is {$this->total} {$currency}");
            $this->printLineDelimiter();
        }
    }

    public function printCartProducts()
    {
        $this->printLine("Your cart contains these products:");
        $this->printLineDelimiter();
        if(empty($this->products))
        {
            $this->printLine("Cart is empty");
        }
        else 
        {
            $this->printLine("ID | Product name | Quantity | Price | Currency");
            foreach($this->products as $product)
            {
                $this->printLine("{$product->id} | {$product->name} | {$product->quantity} | {$product->price} | {$product->currency}");
            }
        }
        $this->printLineDelimiter();
    }

    public function addToCart($product)
    {
        if(isset($this->products[$product->id]))
        {
            $this->updateCartProduct($product);
        }
        else
        {
            $this->products[$product->id] = $product;
        }
    }

    public function removeFromCart($product)
    {
        if(isset($this->products[$product->id]))
        {
            if(abs($product->quantity) >= $this->products[$product->id]->quantity)
            {
                unset($this->products[$product->id]);
            }
            else
            {
                // $product->quantity is negative
                $this->products[$product->id]->quantity += $product->quantity;
            }
        }
        else
        {
            $this->printLine("Couldn't find find product {$product->id}, reduction failed.");
        }
        if(!$this->products)
            $this->products = [];
    }

    public function containsProduct($product)
    {
        return isset($this->products[$product->id]);
    }

    // Not sure how to interpret the update of a cart with given example data. Should it override the old one or should they merge?
    private function updateCartProduct($product)
    {
        $this->products[$product->id]->name = $product->name;
        $this->products[$product->id]->quantity = $product->quantity;
        $this->products[$product->id]->price = $product->price;
        $this->products[$product->id]->currency = $product->currency;
    }

    public function changeDefaultCurrency($currency)
    {
        $validator = new Validator();
        if($validator->isAvailableCurrency($currency))
        {
            $currency = strtoupper($currency);
            $this->printLine("Default currency successfully changed to {$currency}");
            $this->printLineDelimiter();
            self::$default_currency = $currency;
        }
        else
        {
            $validator->printErrors();
        }
    }
}