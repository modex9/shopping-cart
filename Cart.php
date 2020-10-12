<?php 

require_once "Entity.php";

class Cart extends Entity
{
    public $products;

    public $total = 0;
    
    public function __construct($products)
    {
        parent::__construct();
        foreach ($products as $product)
        {
            $this->products[$product->id] = $product;
        }
    }

    public function getTotal()
    {
        foreach ($products as $product)
        {
            $this->total += $product->quantity * $product->getPrice();
        }
    }

    public function printCartTotal($currency = "EUR")
    {
        echo "Cart total is {$this->total} {$currency}";
    }

    public function addToCart($product)
    {
        $this->products[$product->id] = $product;
    }

    public function removeFromCart($id_product, $quantity)
    {
        $reduced_product = getCartProduct($id_product);
        if($reduced_product)
        {
            if($quantity >= $reduced_product)
            {
                unset($this->products[$id_product]);
            }
            else
            {
                $reduced_product->quantity -= $quantity;
                $this->products[$id_product] = $reduced_product;
            }
        }
    }

    private function getCartProduct($id_product)
    {
        return isset($this->products[$id_product]) ? $this->products[$id_product] : null;
    }
}