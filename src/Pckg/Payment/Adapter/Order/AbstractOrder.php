<?php namespace Pckg\Payment\Adapter\Order;

use Pckg\Payment\Adapter\Order;

abstract class AbstractOrder implements Order
{

    protected $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function getCurrency()
    {
        return 'EUR';
    }

}