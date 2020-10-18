<?php

namespace Entity;

class ProductsFileHandler extends FileHandler
{
    public $cart;

    protected const NUM_COLUMNS = 5;

    protected const ENTITY = 'product';

    // Returns array of Products
    public function getProducts()
    {
        $product_lines = $this->getLines();
        if(!$product_lines || (is_array($product_lines) && count($product_lines) == 0))
            return false;

        $products = [];
        $validator = new Validator();
        foreach($product_lines as $i => $product)
        {
            $product_fields = $this->parseEntityLine($product);
            if(!$product_fields)
                continue;

            $product_obj = $this->createProduct($product_fields);
            if($validator->validateProduct($product_obj))
                $products[] = $product_obj;
            else
            {
                $this->printLine("Failed reading product information at line {$product}. Following errors have occured:");
                $validator->printErrors();
                $this->printLineDelimiter();
            }
        }

        return $products;
    }

    public function write($content)
    {
        $product_fields = $this->parseEntityLine($content, false);
        if($product_fields)
        {
            $product = $this->createProduct($product_fields);
            if(ctype_digit($product->quantity) && (int)$product->quantity == 0)
            {
                $this->printLine("Product quantity is 0. Cart update has no effect");
                $this->printLineDelimiter();
                return false;
            }
            $validator = new Validator();
            if($validator->validateProduct($product))
            {
                if(isset($this->cart))
                {
                    $this->printLineDelimiter();
                    if($product->quantity >= 0)
                    {
                        $this->cart->addToCart($product);
                        $this->printLine("{$product->name} was successfully added to your cart.");
                    }
                    else
                    {
                        if($this->cart->containsProduct($product))
                        {
                            $this->cart->removeFromCart($product);
                            $this->printLine("{$product->name} product quantity successfully reduced.");
                        }
                        else
                        {
                            $this->printLine("Product with ID {$product->id} does not exist in the cart, reduction is not possible.");
                            return false;
                        }
                        $this->printLineDelimiter();
                    }

                }
                parent::write($content);
                return true;
            }
            else
            {
                $validator->printErrors();
            }
        }
        return false;
    }

    public function createProduct($product_fields)
    {
        $id = $product_fields[0];
        $name = $product_fields[1];
        $quantity = $product_fields[2];
        $price = $product_fields[3];
        $currency = trim($product_fields[4]);

        $product = new Product($id, $name, $price, $currency, $quantity);
        return $product;
    }
}