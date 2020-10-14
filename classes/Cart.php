<?php 

require_once "ProductsFileHandler.php";

class Cart extends Entity
{
    public $products;

    public $total = 0;
    
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
    }

    public function getTotal()
    {
        $this->total = 0;
        foreach ($this->products as $product)
        {
            $this->total += $product->quantity * $product->getPrice(self::$default_currency);
        }
        $this->total = round($this->total, 2);
    }

    public function printCartTotal()
    {
        $this->getTotal();
        $currency = self::$default_currency;
        echo "Cart total is {$this->total} {$currency}".self::$eol;
        $this->printLineDelimiter();
    }

    public function printCartProducts()
    {
        $eol = self::$eol;
        echo "Your cart contains these products:".$eol;
        $this->printLineDelimiter();
        if(empty($this->products))
        {
            echo "Cart is empty{$eol}";
        }
        else 
        {
            echo "ID | Product name | Quantity | Price | Currency{$eol}";
            foreach($this->products as $product)
            {
                echo "{$product->id} | {$product->name} | {$product->quantity} | {$product->price} | {$product->currency}{$eol}";
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
    }

    private function getCartProduct($id_product)
    {
        return isset($this->products[$id_product]) ? $this->products[$id_product] : null;
    }

    // Not sure how to interpret the update of a cart with given example data. Should it override the old one or should they merge?
    private function updateCartProduct($product)
    {
        $this->products[$product->id]->quantity = $product->quantity;
        $this->products[$product->id]->price = $product->price;
        $this->products[$product->id]->currency = $product->currency;
    }
}