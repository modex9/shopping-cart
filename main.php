<?php

require 'vendor/autoload.php';

use Entity\ProductsFileHandler;
use Entity\Cart;

function printInfo()
{
    echo "Available commands: \n\t update - add/update/remove product from cart. Input format : 'Product ID;Product name;Quantity;Price;Product Currency'
    \n\t\t ;enter Quantity greater than zero to add product
    \n\t\t ;enter Quantity less than zero to reduce quantity of product or remove it completely
    \n\t\t ;if product with already existing ID is entered, then new values will override the olde ones
    \n\t info - print program's manual
    \n\t cart - print cart products
    \n\t total - print total sum of products in the cart
    \n\t change_currency - change default currency
    \n\t exit - exit the program \n";
}

//Read product file and create cart from products.
$products_file_handler = new ProductsFileHandler();
$products = $products_file_handler->getProducts();
$cart = new Cart($products);
$products_file_handler->cart = $cart;

printInfo();


$run = true;
while ($run)
{
    switch ($command = readline("Enter command:"))
    {
        case "update":
        {
            $cart_product = readline("Enter product info:");
            $result = $products_file_handler->write($cart_product);
            if($result)
            {
                $cart->printCartProducts();
                $cart->printCartTotal();
            }
            break;
        }
        case "cart":
            $cart->printCartProducts();
            break;
        case "total":
            $cart->printCartTotal();
            break;
        case "change_currency":
            {
                $currency = readline("Enter currency:");
                $cart->changeDefaultCurrency($currency);
                break;
            }
        case "info":
            printInfo();
            break;
        case "exit":
            $run = false;
            break;
    }
}