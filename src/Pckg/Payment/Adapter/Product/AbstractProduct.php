<?php namespace Pckg\Payment\Adapter\Product;

use Pckg\Payment\Adapter\Product;

abstract class AbstractProduct implements Product
{

    protected $product;

    public function __construct($product)
    {
        $this->product = $product;
    }

}