<?php namespace Pckg\Payment\Adapter\Product;

use Pckg\Payment\Adapter\Product;
use Zone\Listing\Package;

class ZoneProduct extends AbstractProduct implements Product
{

    public function __construct(Package $package)
    {
        $this->product = $package;
    }

    public function getId()
    {
        return $this->product->id;
    }

    public function getName()
    {
        return $this->product->title;
    }

    public function getPrice()
    {
        return $this->product->price;
    }

    public function getQuantity()
    {
        return 1;
    }

    public function getVat()
    {
        return 0;
    }

    public function getTotal()
    {
        return round($this->getQuantity() * $this->getPrice(), 2);
    }

}