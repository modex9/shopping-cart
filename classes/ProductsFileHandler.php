<?php

require_once "FileHandler.php";
require_once "Product.php";

class ProductsFileHandler extends FileHandler
{
    public $columns_delimiter = ';';

    public $cart;

    private const NUM_COLUMNS = 5;

    public function __construct($delimiter = null, $filename = null)
    {
        parent::__construct($filename);
        if($delimiter && Validator::isDelimiter($delimiter))
        {
            $this->columns_delimiter = $delimiter;
        }
    }

    private function getProductLines()
    {
        $file_contents = parent::read();
        if(!$file_contents)
            return false;
        $products = explode("\n", $file_contents);
        return $products;
    }

    // Returns array of Products
    public function getProducts()
    {
        $product_lines = $this->getProductLines();
        if(!$product_lines || (is_array($product_lines) && count($product_lines) == 0))
            return false;

        $products = [];
        foreach($product_lines as $i => $product)
        {
            $product_fields = $this->parseProductLine($product, $i + 1);
            if(!$product_fields)
                continue;

            $id = $product_fields[0];
            $name = $product_fields[1];
            $quantity = $product_fields[2];
            $price = $product_fields[3];
            $currency = trim($product_fields[4]);

            $product_obj = new Product($id, $name, $price, $currency, $quantity);
            $products[] = $product_obj;
        }

        return $products;
    }

    private function parseProductLine($line, $line_number = 0)
    {
        $product_fields = explode($this->columns_delimiter, $line);
        if(count($product_fields) != self::NUM_COLUMNS)
        {
            if($line_number != 0)
                echo "Failed reading product information at line {$line}. Check file formatting.".self::$eol;
            else
                echo "Cart update failed. Check your input formatting.".self::$eol;
            return false;
        }
        return $product_fields;
    }

    public function write($content)
    {
        $eol = self::$eol;
        $product_fields = $this->parseProductLine($content);
        if($product_fields)
        {
            $id = $product_fields[0];
            $name = $product_fields[1];
            $quantity = $product_fields[2];
            $price = $product_fields[3];
            $currency = trim($product_fields[4]);

            $product = new Product($id, $name, $price, $currency, $quantity);
            if(Validator::validateProduct($product))
            {
                if(isset($this->cart))
                {
                    $this->printLineDelimiter();
                    if($product->quantity >= 0)
                    {
                        $this->cart->addToCart($product);
                        echo "{$product->name} was successfully added to your cart.".$eol;
                    }
                    else
                    {
                        if($this->cart->containsProduct($product))
                        {
                            $this->cart->removeFromCart($product);
                            echo "{$product->name} product quantity successfully reduced.".$eol;
                        }
                        else
                        {
                            echo "Product with ID {$product->id} does not exist in the cart, reduction is not possible.".$eol;
                        }
                        $this->printLineDelimiter();
                    }

                }
                parent::write($eol . $content);
                return true;
            }
            else
            {
                foreach (Validator::$errors as $error)
                {
                    echo $error . $eol;
                }
            }

        }
        return false;
    }
}