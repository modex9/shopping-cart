<?php

namespace Entity;

class ProductsFileHandler extends FileHandler
{
    public $columns_delimiter = ';';

    public $cart;

    private const NUM_COLUMNS = 5;

    private function getProductLines()
    {
        $file_contents = parent::read();
        if(!$file_contents)
            return false;
        $products = explode("\r\n", $file_contents);
        return $products;
    }

    // Returns array of Products
    public function getProducts()
    {
        $product_lines = $this->getProductLines();
        if(!$product_lines || (is_array($product_lines) && count($product_lines) == 0))
            return false;

        $products = [];
        $validator = new Validator();
        foreach($product_lines as $i => $product)
        {
            $product_fields = $this->parseProductLine($product);
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

    private function parseProductLine($line, $file = true)
    {
        $product_fields = explode($this->columns_delimiter, $line);
        if(count($product_fields) != self::NUM_COLUMNS)
        {
            if($file)
                $this->printLine("Failed reading product information at line {$line}. Check file formatting.");
            else
                $this->printLine("Cart update failed. Check your input formatting.");
            return false;
        }
        return $product_fields;
    }

    public function write($content)
    {
        $eol = $this->eol;
        $product_fields = $this->parseProductLine($content, false);
        if($product_fields)
        {

            $product = $this->createProduct($product_fields);
            if($product->quantity == 0)
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
                parent::write($eol . $content);
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