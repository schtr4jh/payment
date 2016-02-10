<?php namespace Pckg\Payment\Adapter;

use Pckg\Payment\Adapter\Order;
use Pckg\Payment\Adapter\Order\AbstractOrder;

class WebtostoreOrder extends AbstractOrder implements Order
{

    public function getId()
    {
        return $this->order->id;
    }

    public function getTotal()
    {
        // TODO: Implement getTotal() method.
    }

    public function getVat()
    {
        // TODO: Implement getVat() method.
    }

    public function getDelivery()
    {
        // TODO: Implement getDelivery() method.
    }

    public function getDate()
    {
        // TODO: Implement getDate() method.
    }
}