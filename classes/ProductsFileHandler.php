<?php

require_once "FileHandler.php";
require_once "Product.php";

class ProductsFileHandler extends FileHandler
{
    public $columns_delimiter = ';';

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
        $products = explode("\n", $file_contents);
        return $products;
    }

    // Returns array of Products
    public function getProducts()
    {
        $product_lines = $this->getProductLines();
        if(is_array($product_lines) && count($product_lines) == 0)
            return false;

        $products = [];
        foreach($product_lines as $i => $product)
        {
            $product_fields = explode($this->columns_delimiter, $product);
            if(count($product_fields) != self::NUM_COLUMNS)
            {
                $line = $i + 1;
                echo "Failed reading product information at line {$line}. Check file formatting.\n";
                continue;
            }
            $id = $product_fields[0];
            $name = $product_fields[1];
            $quantity = $product_fields[2];
            $price = $product_fields[3];
            $currency = $product_fields[4];

            $product_obj = new Product($id, $name, $price, $currency, $quantity);
            $products[] = $product_obj;
        }

        return $products;
    }
}