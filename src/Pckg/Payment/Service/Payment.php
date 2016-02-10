<?php namespace Pckg\Payment\Service;

use Pckg\Payment\Adapter\Order;

class Payment
{

    protected $order;

    public function setOrder(Order $order)
    {
        $this->order = $order;
    }

}