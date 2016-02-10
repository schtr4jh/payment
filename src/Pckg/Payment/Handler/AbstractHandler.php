<?php namespace Pckg\Payment\Handler;

use Pckg\Payment\Adapter\Order;

abstract class AbstractHandler implements Handler
{

    protected $config = [];

    protected $dev = true;

    protected $handler;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function isDev()
    {
        return $this->dev;
    }

    public function isProd()
    {
        return !$this->dev;
    }

    public function getTotal()
    {
        return round($this->order->getTotal() * 100);
    }

}