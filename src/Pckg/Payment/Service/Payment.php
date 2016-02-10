<?php namespace Pckg\Payment\Service;

use Pckg\Payment\Adapter\Order;

class Payment
{

    protected $order;

    protected $handler;

    public function setOrder(Order $order)
    {
        $this->order = $order;

        return $this;
    }

    public function setHandler($handler) {
        if (is_string($handler)) {
            $this->handler = new $handler;

        } else if (is_object($handler)) {
            $this->handler = $handler;

        } else {
            throw new \Exception('Invalid payment handler');

        }

        return $this;
    }

}