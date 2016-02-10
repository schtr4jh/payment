<?php namespace Pckg\Payment\Adapter\Product;

use Pckg\Payment\Adapter\Customer\ZoneCustomer;
use Pckg\Payment\Adapter\Order;
use Pckg\Payment\Adapter\Order\AbstractOrder;
use Zone\Listing\Listing;

class ZoneOrder extends AbstractOrder implements Order
{

    public function __construct(Listing $order)
    {
        $this->order = $order;
    }

    public function getId()
    {
        return $this->order->id;
    }

    public function getTotal()
    {
        return $this->order->package->price;
    }

    public function getVat()
    {
        return 0;
    }

    public function getDelivery()
    {
        return 0;
    }

    public function getDate()
    {
        return $this->order->created_at;
    }

    public function getDescription()
    {
        return 'Listing #' . $this->getId();
    }

    public function getCustomer()
    {
        return new ZoneCustomer($this->order->user);
    }

    public function getProducts()
    {
        return [
            new ZoneProduct($this->order->package),
        ];
    }

    public function setPaid()
    {
        $this->order->is_paid = true;
        $this->order->save();
    }

}